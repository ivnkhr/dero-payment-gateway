<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Invoice;
use App\AssociatedTx;

class DataController extends Controller
{
  
    // Invoice
    public function invoiceById($id) {
      
        $invoice = Invoice::where('id', $id)->with('txs')->first();
        
        if(!$invoice) return response()->json(['error'=>'Not Found'], 404);
        return response()->json($invoice, 200);
    }
    
    public function invoiceByPaymentId($payment_id) {
      
        $invoice = Invoice::where('payment_id', $payment_id)->with('txs')->first();
        
        if(!$invoice) return response()->json(['error'=>'Not Found'], 404);
        return response()->json($invoice, 200);
    }
    
    
    // Assosiated Tx
    public function txById($id) {
      
        $tx = AssociatedTx::where('id', $id)->first();
        
        if(!$tx) return response()->json(['error'=>'Not Found'], 404);
        return response()->json($tx, 200);
    }
    
    public function txByHash($tx_hash) {
      
        $tx = AssociatedTx::where('tx', $tx_hash)->first();
        
        if(!$tx) return response()->json(['error'=>'Not Found'], 404);
        return response()->json($tx, 200);
    }
    
    public function txByInvoiceId($invoice_id) {
      
        $tx = AssociatedTx::where('invoice_id', $invoice_id)->first();
        
        if(!$tx) return response()->json(['error'=>'Not Found'], 404);
        return response()->json($tx, 200);
    }
    
    
    // Search function with apgination
    public function search(Request $request) {
      
        $data = Invoice::whereIn('status', [1,2,3]);
        
        $q = $request->search;
        if( $q != null ){
            $data->where(function($query) use($q) {
                $query->where ( 'name', 'LIKE', '%' . $q . '%' );
                $query->orWhere ( 'desc', 'LIKE', '%' . $q . '%' );
                $query->orWhere ( 'price', 'LIKE', '%' . $q . '%' );
                $query->orWhere ( 'status', 'LIKE', '%' . $q . '%' );
                $query->orWhere ( 'payment_id', 'LIKE', '%' . $q . '%' );
                $query->orWhere ( 'integrated_address', 'LIKE', '%' . $q . '%' );
                $query->orWhere ( 'webhook_data', 'LIKE', '%' . $q . '%' );
                $query->orWhere ( 'created_at', 'LIKE', '%' . $q . '%' );
            });
        }
        
        $data = $data->paginate();
        
        return response()->json($data, 200);
    }

}
