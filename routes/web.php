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

use App\Cart;
use App\DiscountCode;
use Illuminate\Support\Facades\Session;

Route::get('/', 'SiteController@index');

Route::prefix('admin')->namespace('Admin')->group(function () {
    Route::get('/', 'AdminController@index');
    Route::get('filemanager', 'AdminController@filemanager');
    create_crud_route('category', 'CategoryController');
    create_crud_route('slider', 'SliderController');
    create_crud_route('brand', 'BrandController');
    create_crud_route('color', 'ColorController');
    Route::get('product/review/primary', 'ReviewController@primary');
    Route::post('product/review/primary', 'ReviewController@addPrimaryContent');
    create_crud_route('product/review', 'ReviewController', []);
    create_crud_route('product', 'ProductController', []);
    create_crud_route('warranty', 'WarrantyController');
    create_crud_route('product_warranties', 'ProductWarrantyController');
    Route::post('comments/change_status', 'CommentController@changeStatus');
    create_crud_route('comments', 'CommentController', ['show', 'create', 'update', 'store', 'edit']);


    create_crud_route('province', 'ProvinceController');
    create_crud_route('city', 'CityController');
    create_crud_route('discount', 'DiscountController');

    Route::get('category/{category}/items', 'ItemController@items');
    Route::post('category/{category}/items', 'ItemController@addItems');
    Route::delete('category/items/{id}', 'ItemController@destroy');

    Route::get('category/{category}/filter', 'FilterController@filters');
    Route::post('category/{category}/filter', 'FilterController@addFilters');
    Route::delete('category/filter/{id}', 'FilterController@destroy');


    Route::get('product/gallery/{id}', 'ProductController@gallery');
    Route::post('product/gallery_upload/{id}', 'ProductController@galleryUpload');
    Route::delete('product/gallery/{id}', 'ProductController@removeImageGallery');
    Route::post('product/gallery/change_image_position/{id}', 'ProductController@changeImagePosition');
    Route::get('product/{id}/items', 'ProductController@items');
    Route::post('product/{id}/items', 'ProductController@addItems');
    Route::get('product/{id}/filters', 'ProductController@filters');
    Route::post('product/{id}/filters', 'ProductController@addFilters');

    Route::get('incredible-offers', 'AdminController@incredible_offers');
    Route::get('ajax/getProductWarranty', 'AdminController@getProductWarranty');
    Route::post('add-incredible-offers/{id}', 'AdminController@addIncredibleOffers');
    Route::post('remove-incredible-offers/{id}', 'AdminController@removeIncredibleOffers');

    Route::match(['get', 'post'], 'setting/send-order-price', 'SettingController@sendOrderPrice');


    Route::get('orders', 'OrderController@index');
    Route::post('orders', 'OrderController@index');
    Route::get('orders/submission', 'OrderController@submission');
    Route::get('orders/submission/approved', 'OrderController@submissionApproved');
    Route::get('orders/submission/items/today', 'OrderController@itemsToday');
    Route::get('orders/submission/ready', 'OrderController@ready');
    Route::get('orders/posting/send', 'OrderController@postingSend');
    Route::get('orders/posting/receive', 'OrderController@postingReceive');
    Route::get('orders/delivered/shipping', 'OrderController@deliveredShipping');
    Route::get('orders/submission/{submission_id}', 'OrderController@submissionInfo');

    Route::get('orders/{order_id}', 'OrderController@show');
    Route::post('order/change_status', 'OrderController@changeStatus');

    Route::post('/editor/upload', 'AdminController@upload')->name('editor-upload');

});
Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::post('add_address', 'UserController@addAddress');
    Route::post('LikeComment', 'ApiController@commentLike');
    Route::post('disLikeComment', 'ApiController@commentDislike');
    Route::delete('remove_address/{id}', 'UserController@removeAddress');
    Route::get('profile/gift-cart ', 'UserpanelController@giftCart');
    Route::get('profile/orders', 'UserpanelController@orders');
    Route::get('profile/orders/{order_id}', 'UserpanelController@showOrders');

});
//Route::get('product/review/create','ReviewController');
//Route::post('product/review/store/','ReviewController');


Route::post('Cart', 'SiteController@addCart');
Route::get('Cart', 'SiteController@showCart');
Route::post('site/getCatBrand', 'SiteController@getCatBrand');
Route::post('get-compare-products', 'SiteController@getCompareProducts');
Route::get('product/comment/{product}', 'SiteController@commentForm')->middleware('auth');
Route::get('product/{product_id}/{product_url}', 'SiteController@showProduct');
Route::get('product/{product_id}', 'SiteController@showProduct');
Route::get('confirm', 'SiteController@confirm')->middleware('guest');
Route::post('active_account', 'SiteController@activeAccount')->middleware('guest')->name('active_account');
Route::post('payment', 'ShoppingController@payment');
Route::get('order/payment', 'ShoppingController@orderPayment');
Route::get('order/verify', 'ShoppingController@verify');
Route::post('site/check-gift-cart', 'ShoppingController@checkGiftCart');
Route::post('site/check-discount-code', 'ShoppingController@checkDiscountCode');

//ajax
Route::post('site/change_color', 'SiteController@changeColor');
Route::post('ajax/resend', 'SiteController@resend')->middleware('guest');;
Route::post('site/cart/remove_product', 'SiteController@removeProduct');
Route::post('site/cart/change_product_cart', 'SiteController@changeProductCart')->middleware('guest');;
Route::get('shipping', 'ShoppingController@shipping')->middleware('auth');
Route::get('shipping/getSendData/{city_id}', 'ShoppingController@getSendData')->middleware('auth');;

Route::get('main/{cat_url}', 'SiteController@showChildCatList');
Route::get('search/{cat_url}', 'SiteController@CatProduct');
Route::get('get-product/search/{cat_url}', 'SiteController@getCatProduct');

Route::get('brand/{brand_name}', 'SiteController@brandProduct');
Route::get('get-product/brand/{brand_name}', 'SiteController@getBrandProduct');
Route::get('compare/{id_product1}/{product_id2?}/{product_id3?}/{product_id4?}', 'SiteController@Compare');
Route::get('product/comment/{product}', 'SiteController@commentForm')->middleware('auth');
Route::post('product/comment/{product}', 'SiteController@addComment')->middleware('auth');
Route::get('site/getComment', 'ApiController@getComment');
Route::get('site/getProductChartData/{product}', 'SiteController@getProductChartData');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Route::post('admin/comment/change_status', function (\Illuminate\Http\Request $request) {
//    return $request->get('comment_id');
//});
