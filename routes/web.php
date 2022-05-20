<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::view('/login','login')->name('login');


Route::get('/', function () {
    return view('welcome');
})->middleware(['verify.shopify'])->name('home');

Route::any('/{any}',function (){
    return view('welcome');
})->where('any','.*')->middleware(['verify.shopify']);
