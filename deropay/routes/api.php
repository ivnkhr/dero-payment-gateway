<?php

use Illuminate\Http\Request;
use App\Http\Middleware\ValidApiAllowInvoice;

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

// Public API
// throttle:5,1 - 12 seconds blocks are 5 request per minute
Route::group(['namespace'=> 'Api', 'prefix' => 'public', 'middleware' => 'throttle:20,1' ], function () {
  
    Route::get('invoice/{payment_id}', 'PublicController@invoiceStatus');
    
});

// Invoice API
Route::group(['namespace'=> 'Api', 'prefix' => 'invoice', 'middleware' => [
    ValidApiAllowInvoice::class,
    'api.secret',
    'api.ip'
  ]], function () {

    Route::post('generate', 'InvoiceController@create');

});

/*
// Data API
Route::group(['middleware' => [ValidApiAllowInvoice::class]], function () {

    Route::get('/generate', function (Request $request) {
        var_dump(config('deropay.secret'));
        die();
    });

});

// Wallet API
Route::group(['middleware' => [ValidApiAllowInvoice::class]], function () {

    Route::get('/generate', function (Request $request) {
        var_dump(config('deropay.secret'));
        die();
    });

});
*/