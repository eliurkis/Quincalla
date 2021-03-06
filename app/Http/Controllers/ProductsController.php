<?php

namespace Quincalla\Http\Controllers;

use Quincalla\Entities\Product;


class ProductsController extends Controller
{
    protected $products;

    public function __construct(Product $products)
    {
        $this->products = $products;
    }

    public function show($slug)
    {
        $product = $this->products->with('collections')
            ->whereSlug($slug)
            ->first();

        return view('product', compact('product'));
    }
}
