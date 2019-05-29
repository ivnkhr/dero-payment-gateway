@extends('layouts.app')

@section('content')

@if( $invoice->status == 1 )
<div class="progress mt-2 mb-0">
  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
</div>
@endif

<div id="invoice_page" data-price="{{ $invoice->price }}" data-confirmations="{{ config('deropay.confirmations') }}" data-status="{{ $invoice->status }}" data-remaining-ttl="{{ $invoice->remaining_ttl }}" data-ttl="{{ $invoice->ttl }}" class="jumbotron mt-2 mb-5 pt-4 pb-4">
  
  <div class="container">
    
    <div class="row align-items-center">
      
      <div class="col-12 col-md-9">
        <h1 class="display-5">{{ $invoice->name }}</h1>
        <p class="lead">{{ $invoice->desc }}</p>
      </div>
      
      <div class="col-3 d-none d-md-block">
        @if( $invoice->status == 1 )
          <div class="stamp waiting">Awaiting Payment</div>
        @endif
        @if( $invoice->status == 2 )
          <div class="stamp outdated">Invoice Outdated</div>
        @endif
        @if( $invoice->status == 3 )
          <div class="stamp paid">Invoice PAID !</div>
        @endif
      </div>
      
      <div class="col-12 d-block d-md-none">
        @if( $invoice->status == 1 )
          <div class="substamp waiting">Status: Awaiting Payment</div>
        @endif
        @if( $invoice->status == 2 )
          <div class="substamp outdated">Status: Invoice Outdated</div>
        @endif
        @if( $invoice->status == 3 )
          <div class="substamp paid">Status: Invoice PAID !</div>
        @endif
      </div>
      
    </div>
    
    <div class="row align-items-center tx-box d-none">
      <table>
        <thead>
          <tr>
            <td>TX</td>
            <td>Confirmations</td>
            <td>Amount</td>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
        <tfoot>
          
        </tfoot>
      </table>
    </div>  
      
      
    <hr class="my-4">
    
    @if( $invoice->status == 1 )
    <div class="alert alert-warning mb-4" role="alert">
        If you are sending funds from exchange, please make sure that final ammount is equal or more then invoice ammount !
    </div>
    @endif
    
    @if( $invoice->status == 2 )
    <div class="alert alert-danger mb-4" role="alert">
        This invoice is now outdated, if you believe that your payment went through, please contact store owner !
    </div>
    @endif
    
    
    @if( $invoice->status == 1 )
    <div id="qr" class="mb-4">{{ $invoice->integrated_address }}</div>
    @endif

    <div class="row align-items-center no-gutters mb-3">
      <div class="col col-3">
        DERO Address
        <a href="#" data-toggle="tooltip" data-placement="top" title="Tooltip on top">&#x1F6C8;</a>
      </div>
      <div class="col">
        <div class="input-group">
          <textarea class="form-control dero-address" placeholder="Address" aria-label="Address" aria-describedby="button-addon2" readonly rows="2">{{ $invoice->integrated_address }}</textarea>
          <div class="input-group-append">
            <button class="btn btn-outline-primary clipboard-copy" type="button" id="button-addon2" data-toggle="tooltip" title="Copied !" data-trigger="focus" data-placement="left">Copy</button>
          </div>
        </div>
      </div>
    </div>


    <div class="row align-items-center no-gutters mb-3">
      <div class="col col-3">
        Invoice Amount
        <a href="#" data-toggle="tooltip" data-placement="top" title="Tooltip on top">&#x1F6C8;</a>
      </div>
      <div class="col">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Invoice Amount" aria-label="Invoice Amount" aria-describedby="button-addon2" readonly value="{{ (($invoice->price / 1000000000000)) }}">
          <div class="input-group-append">
            <button class="btn btn-outline-primary clipboard-copy" type="button" id="button-addon2" data-toggle="tooltip" title="Copied !" data-trigger="focus" data-placement="left">Copy</button>
          </div>
        </div>
      </div>
    </div>

    @if( $invoice->status == 1 )
    <button type="button" class="btn btn-outline-secondary btn-lg btn-block more-info-button mt-5">More Info</button>
    <div class="more-info-box">
    @endif
      
      <div class="row align-items-center no-gutters mb-3">
        <div class="col col-3">
          Payment ID
          <a href="#" data-toggle="tooltip" data-placement="top" title="Tooltip on top">&#x1F6C8;</a>
        </div>
        <div class="col">
          <div class="input-group">
            <input id="payment_id" type="text" class="form-control" placeholder="Payment ID" aria-label="Payment ID" aria-describedby="button-addon2" readonly value="{{ $invoice->payment_id }}">
            <div class="input-group-append">
              <button class="btn btn-outline-primary clipboard-copy" type="button" id="button-addon2" data-toggle="tooltip" title="Copied !" data-trigger="focus" data-placement="left">Copy</button>
            </div>
          </div>
        </div>
      </div>
      
      
      <div class="row align-items-center no-gutters mb-3">
        <div class="col col-3">
          Invoice ID
          <a href="#" data-toggle="tooltip" data-placement="top" title="Tooltip on top">&#x1F6C8;</a>
        </div>
        <div class="col">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Invoice ID" aria-label="Invoice ID" aria-describedby="button-addon2" readonly value="{{ $invoice->id }}">
            <div class="input-group-append">
              <button class="btn btn-outline-primary clipboard-copy" type="button" id="button-addon2" data-toggle="tooltip" title="Copied !" data-trigger="focus" data-placement="left">Copy</button>
            </div>
          </div>
        </div>
      </div>
      
    @if( $invoice->status == 1 )  
    </div>
    @endif
  
  
    @if( $invoice->status == 3 && config('deropay.successurl') )
    <a href="{{ config('deropay.successurl') }}" target="_parent" class="btn btn-success btn-lg btn-block more-info-button mt-5">Back To Store</a>
    @endif
  
</div>

@endsection