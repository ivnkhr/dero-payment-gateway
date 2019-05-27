<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invoice extends Model
{
    
    protected $fillable = ['name', 'desc', 'price', 'ttl', 'webhook_data'];
  
    protected $casts = ['webhook_data' => 'array'];
  
    protected $appends = ['remaining_ttl'];
    
    public function getRemainingTtlAttribute()
    {
        $remaining_ttl = Carbon::now()->diffInSeconds($this->created_at->addSeconds($this->ttl), false);
        return ($remaining_ttl>0)? $remaining_ttl : 0;
    }
    
    /**
     * Get the txs for the invoice record.
     */
    public function txs()
    {
        return $this->hasMany('App\AssociatedTx', 'invoice_id', 'id');
    }
}
