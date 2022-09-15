<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->query('per_page');
        $tags = Tag::with('products')->orderBy('id', 'desc')->paginate($per_page);

        if(!$tags){
            return response()->json([
                "message" => "not found",
            ], 404);
        }

        return response()->json([
            "data" => $tags
        ], 200);    
    }

    public function filterByProduct(Request $request){
        $product = $request->query('product');

        $tags = Tag::with('products')->whereHas('products', function($query) use ($product){
            $query->where('product_name', 'LIKE', "%".$product."%");
        })->get();

        if(!$tags){
            return response()->json([
                "message" => "not found",
            ], 404);
        }
        
        return $tags;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTagRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTagRequest $request)
    {
        $tag = Tag::with('products')->create($request->all());

        if(!$tag){
            return response()->json([
                "message" => "Please fill the tag"
            ], 400);
        } else if ($tag) {
            $tag->products()->create($request->all());
        }

        return response()->json([
            "data" => $tag,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::find($id);

        if(!$tag){
            return response()->json([
                "message" => "Please fill the product"
            ], 400);
        }

        return response()->json([
            "data" => $tag,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTagRequest  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTagRequest $request, $id)
    {
        $tag = Tag::find($id);

        if(!$tag){
            return response()->json([
                "message" => "Please fill the tag"
            ], 400);
        }

        $tag->tag_name = $request->has('tag_name') ? $request->all() : $tag->tag_name;
        $tag->save();

        return response()->json([
            "data" => $tag,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        if(!$tag){
            return response()->json([
                "error" => [
                    "code" => 400,
                    "message" => "Failed."
                ]
            ], 400);
        };

        $tag->delete();
        return response()->json(null, 204);
    }
}
