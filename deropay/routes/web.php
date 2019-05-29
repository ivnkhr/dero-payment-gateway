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

use App\Invoice;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{payment_id}', function ($payment_id) {
    $data['invoice'] = $invoice = Invoice::where('payment_id', $payment_id)->firstOrFail();
    return view('payment', $data);
})->name('invoice')->where('payment_id', '[a-z0-9]{64}+');
