<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->query('per_page');
        $products = Product::with(['licenses', 'tags'])->orderBy('id', 'desc')->paginate($per_page);

        if(!$products){
            return response()->json([
                "message" => "not found",
            ], 404);
        }

        return response()->json([
            "data" => $products
        ], 200);
    }

    public function filterByTag(Request $request)
    {
        $tag = $request->query('tag');
        // $product = Product::whereHas('tags', function(Builder $query){

        //     $query->where('tag_name', 'like', "%$tag%");
        // })->get();
        // $product = Product::where('tags.tag_name', 'LIKE', "%$tag%")->get();
        $product = Product::with(['licenses', 'tags'])->whereHas('tags', function($query) use ($tag){
            $query->where('tag_name', 'LIKE', "%".$tag."%");
        })->get();

        if(!$product){
            return response()->json([
                "message" => "not found",
            ], 404);
        }
        
        return $product;
    }

    public function filterByLicense(Request $request)
    {
        $license = $request->query('license');
        $products = Product::with(['licenses', 'tags'])->whereHas('licenses', function($query) use ($license){
            $query->where('license_name', 'LIKE', "%".$license."%");
        })->get();

        if(!$products){
            return response()->json([
                "message" => "not found",
            ], 404);
        }
        
        return $products;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {

        $product = Product::with(['licenses', 'tags'])->create($request->all());

        if(!$product){
            return response()->json([
                "message" => "Please fill the product"
            ], 400);
        } else if ($product) {
            $product->licenses()->create($request->all());
            $product->tags()->create($request->all());
        }

        return response()->json([
            "data" => $product,
            // "data" => $product->with(['licenses', 'tags']),
            // "data" => $product->with(['licenses', 'tags'])->latest(),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product = Product::find($product);

        if(!$product){
            return response()->json([
                "message" => "Please fill the product"
            ], 400);
        }

        return response()->json([
            "data" => $product,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                "message" => "Please fill the product"
            ], 400);
        }

        $product->product_name = $request->has('product_name') ? $request->all() : $product->product_name;
        $product->save();

        return response()->json([
            "data" => $product,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                "error" => [
                    "code" => 400,
                    "message" => "Failed."
                ]
            ], 400);
        };

        $product->delete();
        return response()->json(null, 204);
    }
}
