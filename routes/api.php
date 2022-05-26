<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

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

Route::any('products',[ProductController::class,'index']);
Route::delete('product/delete/{id}',[ProductController::class,'destroy']);
Route::post('product/update',[ProductController::class,'update']);
Route::post('products/filter',[ProductController::class,'filter']);
Route::post('product/create',[ProductController::class,'create']);
Route::resource('order',OrderController::class);
Route::resource('customer',\App\Http\Controllers\CustomerController::class);

Route::post('customer/filter',[\App\Http\Controllers\CustomerController::class,'filter']);
Route::post('customer/import',[\App\Http\Controllers\CustomerController::class,'import']);
Route::post('customer/export',[\App\Http\Controllers\CustomerController::class,'export']);
Route::post('customer/statusFilter',[\App\Http\Controllers\CustomerController::class,'statusFilter']);
Route::post('customer/datefilter',[\App\Http\Controllers\CustomerController::class,'datefilter']);
