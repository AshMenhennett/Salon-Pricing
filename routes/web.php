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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/terms-of-service', function () {
    return View::make('legal.terms');
})->name('terms');

Route::get('/privacy-policy', function () {
    return View::make('legal.privacy');
})->name('privacy');

Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', 'ProductsController@index')->name('products.index');
        Route::get('/fetch', 'ProductsController@fetchProducts')->name('products.fetch');

        Route::get('/create', 'ProductsController@create')->name('products.create.index');
        Route::post('/create', 'ProductsController@submit')->name('products.create.submit');

        Route::get('/product/{product}/edit', 'ProductsController@edit')->name('products.product.edit.index');
        Route::post('/product/{product}/edit', 'ProductsController@update')->name('products.product.edit.update');

        Route::delete('/product/{product}', 'ProductsController@destroy')->name('products.product.destroy');
    });

    Route::group(['prefix' => 'services'], function () {
        Route::get('/', 'ServicesController@index')->name('services.index');
        Route::get('/fetch', 'ServicesController@fetchServices')->name('services.fetch');

        Route::get('/create', 'ServicesController@create')->name('services.create.index');
        Route::post('/create', 'ServicesController@submit')->name('services.create.submit');

        Route::get('/service/{service}/edit', 'ServicesController@edit')->name('services.service.edit.index');
        Route::post('/service/{service}/edit', 'ServicesController@update')->name('services.service.edit.update');

        Route::delete('/service/{service}', 'ServicesController@destroy')->name('services.service.destroy');
    });

    Route::group(['prefix' => 'prices'], function () {
        Route::get('/products', 'PricingController@products')->name('prices.products');
        Route::get('/services', 'PricingController@services')->name('prices.services');
        Route::get('/all', 'PricingController@all')->name('prices.all');
    });

});
