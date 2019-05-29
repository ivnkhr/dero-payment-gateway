<?php

namespace App\Observers;

use App\Invoice;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class InvoiceStatusObserver
{
    /**
     * Handle the invoice "created" event.
     *
     * @param  \App\Invoice  $invoice
     * @return void
     */
    public function created(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the invoice "updated" event.
     *
     * @param  \App\Invoice  $invoice
     * @return void
     */
    public function updated(Invoice $invoice)
    {
        //
        if($invoice->isDirty('status')){
            // status has changed
            
            $new_status = $invoice->status; 
            $old_status = $invoice->getOriginal('status');
            if(in_array($new_status,[1,2,3]) && config('deropay.webhookurl')){
                // TODO: Send webhook via async Jobs
                $ch = curl_init();
                $invoice->refresh();
                
                $post_data = $invoice->toArray(); 
                $post_data['invoice_signature'] = hash('sha256', $invoice->id.config('deropay.secret'));
                
                $query_data = http_build_query($post_data);
                
                curl_setopt($ch, CURLOPT_URL, config('deropay.webhookurl'));
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $query_data);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000); // The number of milliseconds to wait while trying to connect. Use 0 to wait indefinitely. If libcurl is built to use the standard system name resolver, that portion of the connect will still use full-second resolution for timeouts with a minimum timeout allowed of one second.
				        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                
                if(config('app.debug')==true) Log::info('Request data sent to '.config('deropay.webhookurl').': '.print_r($post_data,true));
                 
                curl_exec($ch);
                curl_close($ch);
            }
        }
    }

    /**
     * Handle the invoice "deleted" event.
     *
     * @param  \App\Invoice  $invoice
     * @return void
     */
    public function deleted(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the invoice "restored" event.
     *
     * @param  \App\Invoice  $invoice
     * @return void
     */
    public function restored(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the invoice "force deleted" event.
     *
     * @param  \App\Invoice  $invoice
     * @return void
     */
    public function forceDeleted(Invoice $invoice)
    {
        //
    }
}
