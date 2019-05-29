<?php

use Illuminate\Http\Request;
use App\Http\Middleware\ValidApiAllowInvoice;
use App\Http\Middleware\ValidApiAllowData;
use App\Http\Middleware\ValidApiAllowWallet;
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
    Route::post('webhook', 'PublicController@webhookTest');
    
});

// Invoice API
Route::group(['namespace'=> 'Api', 'prefix' => 'invoice', 'middleware' => [
    ValidApiAllowInvoice::class,
    'api.secret',
    'api.ip'
  ]], function () {

    Route::post('generate', 'InvoiceController@create');

});


// Data API
Route::group(['namespace'=> 'Api', 'prefix' => 'data', 'middleware' => [
    ValidApiAllowData::class,
    'api.secret',
    'api.ip'
  ]], function () {

    Route::get('invoice/{id}', 'DataController@invoiceById');
    Route::get('invoice/payment_id/{payment_id}', 'DataController@invoiceByPaymentId');
    
    Route::get('tx/{id}', 'DataController@txById');
    Route::get('tx/hash/{tx_hash}', 'DataController@txByHash');
    Route::get('tx/invoice_id/{invoice_id}', 'DataController@txByInvoiceId');

    
    Route::get('invoices', 'DataController@search');
    Route::post('invoices/search', 'DataController@search');

});

// Wallet API
Route::group(['namespace'=> 'Api', 'prefix' => 'wallet', 'middleware' => [
    ValidApiAllowWallet::class,
    'api.secret',
    'api.ip'
  ]], function () {

    Route::get('balance', 'WalletController@balance');
    Route::get('withdraw', 'WalletController@withdraw');
    Route::get('withdrawals', 'WalletController@withdrawals');
    
});
