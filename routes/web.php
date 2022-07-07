<?php

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

Route::get('/', 'SiteController@index');

Route::prefix('admin')->namespace('Admin')->group(function () {
    Route::get('/', 'AdminController@index');
    create_crud_route('category','CategoryController');
    Route::get('category/{category}/items','ItemController@items');
    Route::post('category/{category}/items','ItemController@addItems');
    Route::delete('category/items/{id}','ItemController@destroy');

    Route::get('category/{category}/filter','FilterController@filters');
    Route::post('category/{category}/filter','FilterController@addFilters');
    Route::delete('category/filter/{id}','FilterController@destroy');

    create_crud_route('brand','BrandController');
    create_crud_route('color','ColorController');
    create_crud_route('product','ProductController',true);
    create_crud_route('warranty','WarrantyController');
    create_crud_route('product_warranties','ProductWarrantyController');
    Route::get('product/gallery/{id}','ProductController@gallery');
    Route::post('product/gallery_upload/{id}','ProductController@galleryUpload');
    Route::delete('product/gallery/{id}','ProductController@removeImageGallery');
    Route::post('product/gallery/change_image_position/{id}','ProductController@changeImagePosition');
    Route::get('product/{id}/items','ProductController@items');
    Route::post('product/{id}/items','ProductController@addItems');
    Route::get('product/{id}/filters','ProductController@filters');
    Route::post('product/{id}/filters','ProductController@addFilters');
    create_crud_route('slider','SliderController');


});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
