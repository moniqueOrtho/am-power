<?php

namespace App\Http\Controllers;

use App\Jobs\UploadImage;
use Illuminate\Http\Request;
use App\Http\Resources\ImageResource;
use App\Repositories\Contracts\IPage;
use App\Repositories\Contracts\IImage;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    protected $pages;
    protected $images;

    public function __construct(IImage $images, IPage $pages)
    {

       $this->pages = $pages;
       $this->images = $images;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeImage(Request $request)
    {
        // Validate the request
        $validated = $this->validateUpload($request);
        if($validated) {
            // Get the image
            $upload = $request->file('image');
            $upload_path = $upload->getPathname();

            // Check if image exist. When image exist, destroy this image
            $org_name = $upload->getClientOriginalName();
            $result = $this->images->findNotFailFirst('name', $org_name);

            if(! is_null($result)) {
                $destroyed = $this->destroy($result->id, false);
            }

            // Get the orginal file name and replace any spaces with _, no captials and with timestamp to become unique
            $filename = time()."_". preg_replace('/\$+/', '_', strtolower($org_name));

            // Move the upload to the temporary location (tmp)
            $tmp = $upload->storeAs('uploads/original', $filename, 'tmp');

            // Create the database record for the image
            $image = $this->images->create([
                'user_id' => auth()->user()->id,
                'name' => $filename,
                'alt' => preg_replace('/.jpg|.png|.bmp|.gif/i', '', $org_name),
                'disk' => config('site.upload_disk')
            ]);

            // Dispatch a job to handle the image manipulation
            $this->dispatch(new UploadImage($image));

            return new ImageResource($image);

        } else {
            return response()->json(['message', __('site.input_not_valid')], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $image = $this->images->find($id);

        $this->authorize('update', $image);

        $validated = $this->validateRequest($request, $id);

        if($validated) {
            $updated = $this->images->update($id, [
                'alt' => $request['alt']
            ]);

            return new ImageResource($updated);
        } else {
            return response()->json(['message', __('site.input_not_valid')], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $jsonResponse = true) {

        $image = $this->images->find($id);

        $this->authorize('delete', $image);

        // Delete the files associated to the record
        foreach(['thumbnail', 'original', 'large'] as $size) {
            // Check if files exist in the storage
            if(Storage::disk($image->disk)->exists("uploads/images/{$size}/" . $image->name)) {
                Storage::disk($image->disk)->delete("uploads/images/{$size}/" . $image->name);
            }
        }

        $this->images->delete($id);

        return $jsonResponse ? response()->json(['response' => 1 ], 200) : true ;

    }

    protected function validateUpload(Request $request) {
        $request->validate([
            'image' => ['required', 'mimes:jpg,gif,bmp,png', 'max:2048']
        ]);
        return true;
    }

    protected function validateRequest($request, $id = null)
    {
        $name = $id == null ? 'unique:images,name' : 'unique:images,name,'. $id;

        $request->validate([
            'name' => ['required', 'string', $name],
            'alt' => ['nullable', 'string', 'max:70']
        ]);
        return true;
    }


}
