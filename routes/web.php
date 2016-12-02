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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/terms-of-service', function () {
    return View::make('legal.terms');
})->name('legal.terms');

Route::get('/privacy-policy', function () {
    return View::make('legal.privacy');
})->name('legal.privacy');

// complete Product and All Pricing Components
// check select components
//
// clearn up css
// add inline css to custom
// solidifydesign, make similar
// test
// add to readme
// add to intro home text

Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'services'], function () {
        Route::get('/', 'ServicesController@index')->name('services.index');

        // returns all services, under their respective category, using Fractal
        Route::get('/fetch', 'ServicesController@fetchServices')->name('services.fetch');

        // returns all available categories for select options when creating and editing a service, not to be confused with services.fetch
        Route::get('/fetch/categories', 'ServicesController@fetchServicesWithDistinctCategory')->name('services.fetch.categories');

        Route::get('/create', 'ServicesController@create')->name('services.create.index');
        Route::post('/create', 'ServicesController@submit')->name('services.create.submit');

        Route::get('/{service}/edit', 'ServicesController@edit')->name('services.service.edit.index');
        Route::post('/{service}/edit', 'ServicesController@update')->name('services.service.edit.update');

        Route::delete('/{service}', 'ServicesController@destroy')->name('services.service.destroy');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', 'ProductsController@index')->name('products.index');

        // returns all products, under their respective brand and category, using Fractal
        Route::get('/fetch', 'ProductsController@fetchProducts')->name('products.fetch');

        // returns all available categories for select options when creating and editing a product, not to be confused with products.fetch
        Route::get('/fetch/categories', 'ProductsController@fetchProductsWithDistinctCategory')->name('products.fetch.categories');

        // returns all available brands for select options when creating and editing a product,
        Route::get('/fetch/brands', 'ProductsController@fetchProductsWithDistinctBrand')->name('products.fetch.brands');

        Route::get('/create', 'ProductsController@create')->name('products.create.index');
        Route::post('/create', 'ProductsController@submit')->name('products.create.submit');

        Route::get('/{product}/edit', 'ProductsController@edit')->name('products.product.edit.index');
        Route::post('/{product}/edit', 'ProductsController@update')->name('products.product.edit.update');

        Route::delete('/{product}', 'ProductsController@destroy')->name('products.product.destroy');
    });

    Route::group(['prefix' => 'prices'], function () {
        Route::get('/services', 'PricingController@services')->name('prices.services');
        Route::get('/products', 'PricingController@products')->name('prices.products');
        Route::get('/all', 'PricingController@all')->name('prices.all');
    });

});
