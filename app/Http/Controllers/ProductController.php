<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Tag;
use OpenApi\Annotations as OA;


class ProductController extends Controller
{    
    /**
     * @OA\Get(
     *     path="/api/v0/products",
     *     tags={"Products Data"},
     *     summary="Get Products",
     *     operationId="getProducts",
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
     *                  @OA\Items(type="object", ref="#/components/schemas/Products")
     *              ),
     *              @OA\Property(
     *                  property="first_page_url",
     *                  type="string",
     *                  example="/api/v0/products?page=1"
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

    /**
     * @OA\Get(
     *     path="/api/v0/product-tags",
     *     tags={"Product's Tag Data"},
     *     summary="Get Product's and Tags",
     *     operationId="getProductTags",
     *     
     *     @OA\Parameter(
     *          in="query",
     *          name="per_page",
     *          example="10"
     *     ),
     * 
     *     @OA\Parameter(
     *          in="query",
     *          name="tag",
     *          example="Sony"
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
     *                  @OA\Items(type="object", ref="#/components/schemas/Products")
     *              ),
     *              @OA\Property(
     *                  property="first_page_url",
     *                  type="string",
     *                  example="/api/v0/product-tags?page=1"
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

    public function filterByTag(Request $request)
    {
        $tag = $request->query('tag');
        // $product = Product::whereHas('tags', function(Builder $query){

        //     $query->where('tag_name', 'like', "%$tag%");
        // })->get();
        // $product = Product::where('tags.tag_name', 'LIKE', "%$tag%")->get();
        $products = Product::with(['licenses', 'tags'])->whereHas('tags', function($query) use ($tag){
            $query->where('tag_name', 'LIKE', "%".$tag."%");
        })->get();

        if(!$products){
            return response()->json([
                "message" => "not found",
            ], 404);
        }
        
        return response()->json([
            "data" => $products
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v0/product-licenses",
     *     tags={"Product's License Data"},
     *     summary="Get Product's and Licenses",
     *     operationId="getProductLicenses",
     *     
     *     @OA\Parameter(
     *          in="query",
     *          name="per_page",
     *          example="10"
     *     ),
     * 
     *     @OA\Parameter(
     *          in="query",
     *          name="license",
     *          example="iBM"
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
     *                  @OA\Items(type="object", ref="#/components/schemas/Products")
     *              ),
     *              @OA\Property(
     *                  property="first_page_url",
     *                  type="string",
     *                  example="/api/v0/product-licenses?page=1"
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
        
        return response()->json([
            "data" => $products
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v0/products",
     *     tags={"Post Products Data"},
     *     summary="Post Products",
     *     operationId="postProducts",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="product_name", type="string", example="Jack Daniel"),
     *              @OA\Property(property="license_name", type="string", example="HACCP"),
     *              @OA\Property(property="tag_name", type="string", example="Pilsner"),
     *          ),
     *      ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Product has been created",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Products")
     *          ),
     *     ),
     * 
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
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
        };

        return response()->json([
            "data" => $product->with(['licenses', 'tags'])->first(),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v0/products/{id}",
     *     tags={"Products by Id Data"},
     *     summary="Get Product by Id",
     *     operationId="getProductId",
     *     
     *     @OA\Parameter(
     *          in="path",
     *          required=true,
     *          name="id",
     *          description="The id of the product",
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
     *                  @OA\Items(type="object", ref="#/components/schemas/Products")
     *              ),
     *              @OA\Property(
     *                  property="first_page_url",
     *                  type="string",
     *                  example="/api/v0/products?page=1"
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
     * @OA\Put(
     *     path="/api/v0/products/{id}",
     *     tags={"Update Product Data"},
     *     summary="Update Product Data",
     *     operationId="updateProduct",
     *     @OA\Parameter(
     *          in="path",
     *          required=true,
     *          name="id",
     *          description="The id of the product",
     *          @OA\Schema(
     *              type="integer",
     *              example="1"
     *          ),
     *     ),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="product_name", type="string", example="Vivo"),
     *          ),
     *
     *      ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Product has been updated",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Products")
     *          ),
     *     ),
     * 
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
     */

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id)->first();

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
     * @OA\Delete(
     *     path="/api/v0/products/{id}",
     *     tags={"Delete Product Data"},
     *     summary="Delete Product",
     *     operationId="deleteProduct",
     *     @OA\Parameter(
     *          in="path",
     *          required=true,
     *          name="id",
     *          description="The id of the product",
     *          @OA\Schema(
     *              type="integer",
     *              example="1"
     *          ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product has been deleted",
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
