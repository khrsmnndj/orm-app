<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreLicenseRequest;
use App\Http\Requests\UpdateLicenseRequest;
use App\Models\License;
use OpenApi\Annotations as OA;

class LicenseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v0/licenses",
     *     tags={"Licenses Data"},
     *     summary="Get Licenses",
     *     operationId="getLicenses",
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
     *                  @OA\Items(type="object", ref="#/components/schemas/Licenses")
     *              ),
     *              @OA\Property(
     *                  property="first_page_url",
     *                  type="string",
     *                  example="/api/v0/licenses?page=1"
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
        $licenses = License::orderBy('id', 'desc')->paginate($per_page);

        if(!$licenses){
            return response()->json([
                "message" => "not found",
            ], 404);
        }

        return response()->json([
            "data" => $licenses
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v0/licenses",
     *     tags={"Post Licenses Data"},
     *     summary="Post Licenses",
     *     operationId="postLicenses",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="license_name", type="string", example="HACCP"),
     *              @OA\Property(property="product_id", type="integer", example="1013"),
     *          ),
     *      ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="License has been created",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Licenses")
     *          ),
     *     ),
     * 
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
     */

    public function store(StoreLicenseRequest $request)
    {
        $licenses = License::create($request->all());

        if(!$licenses){
            return response()->json([
                "message" => "Please fill the licenses"
            ], 400);
        }

        return response()->json([
            "data" => $licenses,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v0/licenses/{id}",
     *     tags={"Licenses by Id Data"},
     *     summary="Get License by Id",
     *     operationId="getLicenseId",
     *     
     *     @OA\Parameter(
     *          in="path",
     *          required=true,
     *          name="id",
     *          description="The id of the license",
     *          @OA\Schema(
     *              type="integer",
     *              example="21"
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
     *                  @OA\Items(type="object", ref="#/components/schemas/Licenses")
     *              ),
     *              @OA\Property(
     *                  property="first_page_url",
     *                  type="string",
     *                  example="/api/v0/licenses?page=1"
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
        $licenses = License::find($id);
        return response()->json([
            "data" => $licenses
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/v0/licenses/{id}",
     *     tags={"Update License Data"},
     *     summary="Update License Data",
     *     operationId="updateLicense",
     *     @OA\Parameter(
     *          in="path",
     *          required=true,
     *          name="id",
     *          description="The id of the license",
     *          @OA\Schema(
     *              type="integer",
     *              example="21"
     *          ),
     *     ),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="license_name", type="string", example="ISO 5001"),
     *              @OA\Property(property="product_id", type="integer", example="1018"),
     *          ),
     *
     *      ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="License has been updated",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Licenses")
     *          ),
     *     ),
     * 
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     * )
     */

    public function update(UpdateLicenseRequest $request, $id)
    {
        $license = License::find($id);

        if(!$license){
            return response()->json([
                "message" => "Please fill the license"
            ], 400);
        }

        $license->license_name = $request->has('license_name') ? $request->all() : $license->license_name;
        $license->product_id = $request->has('product_id') ? $request->all() : $license->product_id;
        $license->save();

        return response()->json([
            "data" => $license,
        ], 201);        
    }

    /**
     * @OA\Delete(
     *     path="/api/v0/licenses/{id}",
     *     tags={"Delete license Data"},
     *     summary="Delete License",
     *     operationId="deleteLicense",
     *     @OA\Parameter(
     *          in="path",
     *          required=true,
     *          name="id",
     *          description="The id of the license",
     *          @OA\Schema(
     *              type="integer",
     *              example="1"
     *          ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="license has been deleted",
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
        $license = License::find($id);
        if(!$license){
            return response()->json([
                "error" => [
                    "code" => 400,
                    "message" => "Failed."
                ]
            ], 400);
        };

        $license->delete();
        return response()->json(null, 201);
    }
}
