<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Invoice;
use App\AssociatedTx;

class ResoluteInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:resolute {payment_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will scan wallet and close open invoices with success or outdated status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      
        //
        $this->info('Start comparing open invoices to incoming transactions');
        
        
        // Get all pending invoices (status = 0/1)
        $payment_id = $this->argument('payment_id');
        if( $payment_id && $payment_id != 'all' ) {
            $this->info('Comparing specific invoice');
            $open_invoices = Invoice::where('status', 1)->where('payment_id', $payment_id)->get();
        }else{
            $this->info('Comparing all invoices');
            $open_invoices = Invoice::where('status', 1)->get();
        } 

        // Assign apropriet txs to invoice instances
        $payment_ids = [];
        $original_invoices = [];
        foreach( $open_invoices as $invoice){
            $payment_ids[] = $invoice->payment_id;
            $original_invoices[$invoice->payment_id] = &$invoice;
        }
        if( count($payment_ids) > 0 ){
            
            $client = new \GuzzleHttp\Client(['headers' => [ 'Content-Type' => 'application/json' ]]);
            $request_data = array(
                'jsonrpc' => '2.0',
                'id' => 0,
                'method' => 'get_bulk_payments',
                'params' => array(
                    'payment_ids' => $payment_ids,
                    'min_block_height' => 0 //TODO can be further improved
                )
            );
            
            try{
                $response_data = $client->request('POST', config('deropay.wallet').'json_rpc', array('json' => $request_data));
                if($response_data->getStatusCode() == 200){
                    $response = json_decode($response_data->getBody()->getContents());
                    $payments = @$response->result->payments;
                    if( isset($payments) && is_array($payments) ){
                      
                        // Get current wallet height
                        // $client = new \GuzzleHttp\Client(['headers' => [ 'Content-Type' => 'application/json' ]]);
                        try{
                            $height_response_data = $client->request('POST', config('deropay.wallet').'json_rpc', array('json' => array(
                                'jsonrpc' => '2.0',
                                'id' => 0,
                                'method' => 'getheight',
                            )));
                            if($height_response_data->getStatusCode() == 200){
                                $height_response = json_decode($height_response_data->getBody()->getContents());
                            }
                        }catch(\Exception $e){
                            return response()->json(['error' => 'Failed to communicate with wallet api endpoint'], 503);
                        }
                      
                        // If there results for invoice, extend its ttl for confirmation purposes
                        foreach($payments as $payment){
                            if( isset($original_invoices[$payment->payment_id]) ){
                                // Found tx for open invoice
                                $tx = AssociatedTx::firstOrNew(['tx' => $payment->tx_hash]);
                                $tx->tx = $payment->tx_hash;
                                $tx->height = $payment->block_height; 
                                $tx->wallet_height = $height_response->result->height; 
                                $tx->amount = $payment->amount; 
                                $tx->invoice_id = $original_invoices[$payment->payment_id]->id;
                                $tx->save();
                                //$original_invoices[$payment->payment_id]->ttl += config('deropay.confirmations') * 30;
                            }
                        }
                    }
                }
            }catch(\Exception $e){
                // var_dump($e->getMessage());
                return response()->json(['error' => 'Failed to communicate with wallet api endpoint'], 503);
            }
            
        }
        
        
        // Calculate all txs for open invoices and close them
        foreach( $original_invoices as $invoice){
            if( count($invoice->txs) > 0 ){
                $invoice_tx_sum = 0;
                foreach($invoice->txs as $tx){
                    if(($tx->wallet_height - $tx->height) >= config('deropay.confirmations'))
                        $invoice_tx_sum += (int)$tx->amount;
                }
                if($invoice_tx_sum >= (int)$invoice->price){
                    $invoice->status = 3;
                    $invoice->save();
                }
            }
        }
        
        
        // Close all outdated requests
        foreach( $original_invoices as $invoice){
            if( $invoice->remaining_ttl < 1 && $invoice->status == 1 ){
                $invoice->status = 2; //outdated
                $invoice->save();
            }
        }
        
        
        // Delete all uninitiated requests
        Invoice::whereIn('status', [0])->delete();
        
    }
}
