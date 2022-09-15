<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\TagController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v0')->group(function () {
    // Route::prefix('auth')->controller(AuthController::class)->group(function () 
    // {
    //     Route::get('/user', 'authUser');
    //     Route::post('/login', 'login')->name('login');
    //     Route::post('/logout', 'logout')->name('logout');
    // });
    Route::apiResource('licenses', LicenseController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('tags', TagController::class);
    Route::controller(ProductController::class)->group(function (){
        Route::get('/product-tags','filterByTag');
        Route::get('/product-licenses','filterByLicense');
    });

    Route::controller(TagController::class)->group(function (){
        Route::get('/tag-products','filterByProduct');
    });


});

