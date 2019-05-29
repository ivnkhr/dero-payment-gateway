<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\AssociatedTx;

class WalletController extends Controller
{
    // Get balance
    public function balance(Request $request)
    {
        $client = new \GuzzleHttp\Client(['headers' => [ 'Content-Type' => 'application/json' ]]);
        $request_data = array(
            'jsonrpc' => '2.0',
            'id' => 0,
            'method' => 'getbalance',
            'params' => array()
        );
        
        $balance = null;
        
        try{
            $response_data = $client->request('POST', config('deropay.wallet').'json_rpc', array('json' => $request_data));
            if($response_data->getStatusCode() == 200){
                $response = json_decode($response_data->getBody()->getContents());
                $balance = ($response->result);
                $result = $balance;
                $result->balance_unit = $balance->balance/1000000000000;
                $result->unlocked_balance_unit = $balance->unlocked_balance/1000000000000;
            }
            if( $balance == null ) throw new \Exception();
        }catch(\Exception $e){
            return response()->json(['error' => 'Failed to communicate with wallet api endpoint'], 503);
        }
        
        return response()->json($result, 200);
    }
    
    //
    public function withdraw(Request $request)
    {
        if( config('deropay.coldstorage') ){
          
            $balance = (array) ($this->balance($request)->original);

            if(isset($balance['unlocked_balance']) && (int) $balance['unlocked_balance'] > 1 + (int) config('deropay.withdrawfee')){

                $withdraw_amount = $balance['unlocked_balance'] - (int) config('deropay.withdrawfee') - 1;

                $client = new \GuzzleHttp\Client(['headers' => [ 'Content-Type' => 'application/json' ]]);
                $request_data = array(
                    'jsonrpc' => '2.0',
                    'id' => 0,
                    'method' => 'transfer',
                    'params' => array(
                        'destinations' => array([
                            'amount' => (int) ($withdraw_amount),
                            'address' => config('deropay.coldstorage')
                        ])
                    )
                );
                
                if( (int) $withdraw_amount < (int) config('deropay.threshold') )
                    return response()->json(['error' => 'Hot wallet balance is below withdrawal threshold'], 403);
                
                $withdrawn_tx = null;
                
                try{
                    $response_data = $client->request('POST', config('deropay.wallet').'json_rpc', array('json' => $request_data));
                    if($response_data->getStatusCode() == 200){
                        $response = json_decode($response_data->getBody()->getContents());

                        if( isset($response->result) ){
                            $payment = $response->result;
                            $tx = AssociatedTx::firstOrNew(['tx' => $payment->tx_hash]);
                            $tx->tx = $payment->tx_hash;
                            $tx->height = 0; 
                            $tx->wallet_height = 0; 
                            $tx->amount = $withdraw_amount; 
                            $tx->invoice_id = 0;
                            $tx->save();
                            $withdrawn_tx = ($tx->tx);
                        }
                    }
                    if( $withdrawn_tx == null ) throw new \Exception();
                }catch(\Exception $e){
                    return response()->json(['error' => 'Failed to withdraw, insufficient balance'], 503);
                }

                $result['withdrawn_tx'] = $withdrawn_tx;
                return response()->json($result, 200);
                
            }
            return response()->json(['error' => 'Failed to communicate with wallet api endpoint'], 503);
        }
        return response()->json(['error' => 'Cold storage address not configured'], 403);
    }
    
    // List of withdrawals
    public function withdrawals(Request $request) {
      
        $withdrawals = AssociatedTx::where('invoice_id', '=', 0)->paginate();
        return response()->json($withdrawals, 200);
    }
}
