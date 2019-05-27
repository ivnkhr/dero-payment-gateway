<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Invoice;

class InvoiceController extends Controller
{
    public function create(Request $request)
    {
        // Create invoice draft
        $invoice = new Invoice;
        
        $invoice->fill($request->all());
        $invoice->status = 0;
        $invoice->save();
        
        // Craete integrated adress and payment_id for fresh invoice
        $payment_id = hash('sha256', $invoice->id.config('app.key'));

        $client = new \GuzzleHttp\Client(['headers' => [ 'Content-Type' => 'application/json' ]]);
        $request_data = array(
            'jsonrpc' => '2.0',
            'id' => 0,
            'method' => 'make_integrated_address',
            'params' => array(
                'payment_id' => $payment_id,
            )
        );
        
        $integrated_address = '';
        
        try{
            $response_data = $client->request('POST', config('deropay.wallet').'json_rpc', array('json' => $request_data));
            if($response_data->getStatusCode() == 200){
                $response = json_decode($response_data->getBody()->getContents());
                $integrated_address = ($response->result->integrated_address);
            }
            if( strlen($integrated_address) != 142 ) throw new \Exception();
        }catch(\Exception $e){
            return response()->json(['error' => 'Failed to communicate with wallet api endpoint'], 503);
        }
        
        $invoice->payment_id = $payment_id;
        $invoice->integrated_address = $integrated_address;
        $invoice->status = 1;
        $invoice->save();
        
        $result = $invoice->fresh();
        $result->payment_url = config('app.url').'/'.$payment_id;

        return response()->json($result, 200);
    }
}
