<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Invoice;
use Illuminate\Support\Facades\Log;

class PublicController extends Controller
{
    //
    public function invoiceStatus($payment_id) {
        \Artisan::call('invoice:resolute '.$payment_id);
      
        $invoice = Invoice::where('payment_id', $payment_id)->firstOrFail();
        $txs = [];
        foreach($invoice->txs as $tx){
            $confirmations = ($tx->wallet_height - $tx->height);
            $txs[] = array(
                'obfuscated_tx' => substr($tx->tx, 0, 16),
                'confirmations' => ($confirmations<=0)?0:$confirmations,
                'amount' => $tx->amount
            );
        }
        return response()->json([
            'remaining_ttl' => $invoice->remaining_ttl,
            'status' => $invoice->status,
            'txs' => ($invoice->status==1)?$txs:[]
        ], 200);
    }
    
    //
    public function webhookTest(Request $request) {
        Log::info('Request data recieved: ', [$request->all()]);
        return response()->json([], 200);
    }
}
