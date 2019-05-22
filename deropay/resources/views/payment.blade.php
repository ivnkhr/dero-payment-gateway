@extends('layouts.app')

@section('content')

<div class="progress mt-4 mb-0">
  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">12:51 Left</div>
</div>

<div class="jumbotron mt-2 mb-5 pt-4 pb-4">
  
  <div class="container">
    
    <div class="row align-items-center">
      
      <div class="col">
        <h1 class="display-5">ProductName</h1>
        <p class="lead">Product description</p>
      </div>
      
      <div class="col-3">
        <div class="stamp paid">PAID</div>
      </div>
      
    </div>
    
    <hr class="my-4">
    
    <div class="alert alert-warning mb-4" role="alert">
        A simple warning alert—check it out!
    </div>
    
    <div id="qr" class="mb-4">dERodvQrEpaHgZQSSB6LKdMdLA7kZVpvXGZmVE62Efcpbp9HerMmhUNLWZGxs9W4xtUNHr9iwM9cuHiqzjCrxN8W35ZsThuANs</div>

    <div class="row align-items-center no-gutters mb-3">
      <div class="col col-3">
        Address
        <a href="#" data-toggle="tooltip" data-placement="top" title="Tooltip on top">&#x1F6C8;</a>
      </div>
      <div class="col">
        <div class="input-group">
          <textarea class="form-control dero-address" placeholder="Address" aria-label="Address" aria-describedby="button-addon2" readonly rows="2">dERodvQrEpaHgZQSSB6LKdMdLA7kZVpvXGZmVE62Efcpbp9HerMmhUNLWZGxs9W4xtUNHr9iwM9cuHiqzjCrxN8W35ZsThuANs</textarea>
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
          <input type="text" class="form-control" placeholder="Invoice Amount" aria-label="Invoice Amount" aria-describedby="button-addon2" readonly value="5">
          <div class="input-group-append">
            <button class="btn btn-outline-primary clipboard-copy" type="button" id="button-addon2" data-toggle="tooltip" title="Copied !" data-trigger="focus" data-placement="left">Copy</button>
          </div>
        </div>
      </div>
    </div>


    <button type="button" class="btn btn-outline-secondary btn-lg btn-block more-info-button mt-5">More Info</button>
    
    
    <div class="more-info-box">
      
      
      <div class="row align-items-center no-gutters mb-3">
        <div class="col col-3">
          Payment ID
          <a href="#" data-toggle="tooltip" data-placement="top" title="Tooltip on top">&#x1F6C8;</a>
        </div>
        <div class="col">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Payment ID" aria-label="Payment ID" aria-describedby="button-addon2" readonly value="5">
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
            <input type="text" class="form-control" placeholder="Invoice ID" aria-label="Invoice ID" aria-describedby="button-addon2" readonly value="5">
            <div class="input-group-append">
              <button class="btn btn-outline-primary clipboard-copy" type="button" id="button-addon2" data-toggle="tooltip" title="Copied !" data-trigger="focus" data-placement="left">Copy</button>
            </div>
          </div>
        </div>
      </div>
      
      
    </div>
  
  
</div>

@endsection