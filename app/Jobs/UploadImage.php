<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\Image as ImageModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $image;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ImageModel $image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = $this->image->disk;
        $filename = $this->image->name;

        $original_file = storage_path('uploads/original/' . $filename);

        try {

            // Create the large Image and save to tmp disk
            Image::make($original_file)
                ->fit(800, null, function($constraint){
                    $constraint->aspectRatio();
                })
                ->save($large = storage_path('uploads/large/' . $filename));

            // Create the thumbnail Image and save to tmp disk
            Image::make($original_file)
            ->fit(250, 250, function($constraint){
                $constraint->aspectRatio();
            })
            ->save($thumbnail = storage_path('uploads/thumbnail/' . $filename));

            // Store images to permanent disk
            // Original image
            if(Storage::disk($disk)
                ->put('/uploads/images/original/' . $filename, fopen($original_file, 'r+'))) {
                    File::delete($original_file);
                }
            // Large image
            if(Storage::disk($disk)
                ->put('/uploads/images/large/' . $filename, fopen($large, 'r+'))) {
                    File::delete($large);
                }
            // thumbnail image
            if(Storage::disk($disk)
                ->put('/uploads/images/thumbnail/' . $filename, fopen($thumbnail, 'r+'))) {
                    File::delete($thumbnail);
                }
            // Update the database record with succes flag
            $this->image->update([
                'upload_successful' => true
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
