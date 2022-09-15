<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreLicenseRequest;
use App\Http\Requests\UpdateLicenseRequest;
use App\Models\License;

class LicenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $licenses = License::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
        $license->save();

        return response()->json([
            "data" => $license,
        ], 201);        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
        return response()->json(null, 204);
    }
}
