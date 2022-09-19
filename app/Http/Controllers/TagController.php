<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\Request;
use App\Models\Tag;
use OpenApi\Annotations as OA;

class TagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v0/tags",
     *     tags={"Tags Data"},
     *     summary="Get Tags",
     *     operationId="getTags",
     *     
     *     @OA\Parameter(
     *          in="query",
     *          name="per_page",
     *          example="10"
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="current_page",
     *                  type="integer",
     *                  example="1"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(type="object", ref="#/components/schemas/Tags")
     *              ),
     *              @OA\Property(
     *                  property="first_page_url",
     *                  type="string",
     *                  example="/api/v0/tags?page=1"
     *              ),
     *              @OA\Property(
     *                  property="from",
     *                  type="integer",
     *                  example="1"
     *              ),
     *              @OA\Property(
     *                  property="last_page",
     *                  type="integer",
     *                  example="10"
     *              ),
     *          ),
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
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

        /**
     * @OA\Get(
     *     path="/api/v0/tag-products",
     *     tags={"Tag's Products Data"},
     *     summary="Get Tags and Products",
     *     operationId="getTagProducts",
     *     
     *     @OA\Parameter(
     *          in="query",
     *          name="per_page",
     *          example="10"
     *     ),
     * 
     *     @OA\Parameter(
     *          in="query",
     *          name="product",
     *          example="window"
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="current_page",
     *                  type="integer",
     *                  example="1"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(type="object", ref="#/components/schemas/Tags")
     *              ),
     *              @OA\Property(
     *                  property="first_page_url",
     *                  type="string",
     *                  example="/api/v0/tag-products?page=1"
     *              ),
     *              @OA\Property(
     *                  property="from",
     *                  type="integer",
     *                  example="1"
     *              ),
     *              @OA\Property(
     *                  property="last_page",
     *                  type="integer",
     *                  example="10"
     *              ),
     *          ),
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */

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
     * @OA\Post(
     *     path="/api/v0/tags",
     *     tags={"Post Tags Data"},
     *     summary="Post Tags",
     *     operationId="postTags",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="tag_name", type="string", example="Pilsner"),
     *              @OA\Property(property="product_name", type="string", example="Jack Daniel"),
     *          ),
     *      ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Tag has been created",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Tags")
     *          ),
     *     ),
     * 
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
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
            "data" => $tag->with('products')->first(),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v0/tags/{id}",
     *     tags={"Tags by Id Data"},
     *     summary="Get Tag by Id",
     *     operationId="getTagId",
     *     
     *     @OA\Parameter(
     *          in="path",
     *          required=true,
     *          name="id",
     *          description="The id of the tag",
     *          @OA\Schema(
     *              type="integer",
     *              example="1"
     *          ),
     *     ),
     *     
     *     @OA\Parameter(
     *          in="query",
     *          name="per_page",
     *          example="10"
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="current_page",
     *                  type="integer",
     *                  example="1"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(type="object", ref="#/components/schemas/Tags")
     *              ),
     *              @OA\Property(
     *                  property="first_page_url",
     *                  type="string",
     *                  example="/api/v0/tags?page=1"
     *              ),
     *              @OA\Property(
     *                  property="from",
     *                  type="integer",
     *                  example="1"
     *              ),
     *              @OA\Property(
     *                  property="last_page",
     *                  type="integer",
     *                  example="10"
     *              ),
     *          ),
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/v0/tags/{id}",
     *     tags={"Update Tag Data"},
     *     summary="Update Tag Data",
     *     operationId="updateTag",
     *     @OA\Parameter(
     *          in="path",
     *          required=true,
     *          name="id",
     *          description="The id of the tag",
     *          @OA\Schema(
     *              type="integer",
     *              example="1"
     *          ),
     *     ),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="tag_name", type="string", example="Sony"),
     *          ),
     *
     *      ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Tag has been updated",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Tags")
     *          ),
     *     ),
     * 
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
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
     * @OA\Delete(
     *     path="/api/v0/tags/{id}",
     *     tags={"Delete Tag Data"},
     *     summary="Delete Tag",
     *     operationId="deleteTag",
     *     @OA\Parameter(
     *          in="path",
     *          required=true,
     *          name="id",
     *          description="The id of the tag",
     *          @OA\Schema(
     *              type="integer",
     *              example="1"
     *          ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tag has been deleted",
     *     ),
     * 
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
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
