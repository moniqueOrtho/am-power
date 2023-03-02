<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\ISite;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\Contracts\IProduct;
use App\Repositories\Eloquent\Criteria\LatestFirst;

class ProductController extends Controller
{
    protected $products;
    protected $sites;

    public function __construct(ISite $sites, IProduct $products)
    {

       $this->sites = $sites;
       $this->products = $products;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $siteId)
    {
        $result = $this->products->withCriteria([
            new LatestFirst()
        ])->findWhere('site_id', $siteId);

        if($request->ajax()) {
            return ProductResource::collection($result);
        }

        $result = $result->map(function($product) {
            $data = [
                'id' => $product->id,
                'name' => $product->name,
                'title' => $product->title,
                'description' => $product->description,
                'type' => $product->type,
                'stock' => $product->stock,
                'price' => $product->price,
                'VAT' => $product->VAT,
                'expand' => '<p class="font-weight-bold py-2 mb-0 primary--text">' .$product->title . ':</p><p><span class="text-decoration-underline">'. trans_choice('site.descriptions', 1) . ':</span><span> ' . $product->description . '</span></p>',
            ];

            return $data;
        });


        $sites = null;

        if($siteId > 0) {
            $ownSites = auth()->user()->ownedSites;
            $sites = $ownSites->map(function ($site){
                return [
                    'text' => $site->title,
                    'value' => $site->id
                ];
            });
        }

        return view('admin.products', ['data' => $result, 'siteId' => $siteId, 'sites' => $sites ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Authorize request
        if(isset($request['site_id'])) {
            $site = $this->sites->find($request['site_id']);
        } else {
            $site = auth()->user()->ownedSites->first();
        }

        $this->authorize('create', [Product::class, $site]);

        $validated = $this->validateRequest($request);

        // Create Product
        if($validated) {
            $newProduct = $this->products->create([
                'site_id' => $site->id,
                'name' => $request['name'],
                'title' => $request['title'] ?? $request['name'],
                'description' => $request['description'] ?? null,
                'type' => $request['type'] ?? null,
                'stock' => $request['stock'] ?? 0,
                'price' => $request['price'],
                'VAT' => $request['VAT'] ?? 0,
            ]);

            return new ProductResource($newProduct);
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
        // Authorize request
        if(isset($request['site_id'])) {
            $site = $this->sites->find($request['site_id']);
        } else {
            $site = auth()->user()->ownedSites->first();
        }
        $this->authorize('update', [Product::class, $site]);

        // Validate request
         $validated = $this->validateRequest($request, $id);

         if($validated) {

            $updated = $this->products->update($id, [
                'site_id' => $site->id,
                'name' => $request['name'],
                'title' => $request['title'],
                'description' => $request['description'],
                'type' => $request['type'],
                'stock' => $request['stock'],
                'price' => $request['price'],
                'VAT' => $request['VAT'],
            ]);

            return new ProductResource($updated);

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
    public function destroy($id)    {

        // Authorize request
        $product = $this->products->find($id);
        $site = $this->sites->find($product->site_id);
        $this->authorize('delete', [Product::class, $site]);

        $this->products->delete($id);

        return response()->json(['response' => 1 ], 200);
    }


    protected function validateRequest($request, $id = null)
    {

        $name = $id == null ? 'unique:products,name' : 'unique:products,name,'. $id;

        $request->validate([
            'name' => ['required', 'string', 'max:250', $name],
            'title' => ['nullable', 'string', 'max:250'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'string'],
            'stock' => ['nullable', 'numeric', 'min:0', 'max: 100'],
            'price' => ['required', 'numeric', 'min:0'],
            'VAT' => ['nullable', 'numeric', 'min:0', 'max: 100'],
        ]);
        return true;
    }

}


