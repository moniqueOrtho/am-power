<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\ISite;
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
    public function index($siteId)
    {
        $result = $this->products->withCriteria([
            new LatestFirst()
        ])->findWhere('site_id', $siteId);

        $result = $result->map(function($product) {
            $data = [
                'id' => $product->id,
                'name' => $product->name,
                'title' => $product->title,
                'description' => $product->description,
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

}


