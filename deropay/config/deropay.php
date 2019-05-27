<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DeroPay Configs
    |--------------------------------------------------------------------------
    |
    |
    */

    'secret' => env('DEROPAY_API_SECRET', ''),
	
    'apiaccess' => [
      
      'invoice' => env('DEROPAY_API_ALLOW_INVOICE', false),
      'data' => env('DEROPAY_API_ALLOW_DATA', false),
      'wallet' => env('DEROPAY_API_ALLOW_WALLET', false),
      'ip' => env('DEROPAY_API_ALLOWED_IP', '*'),
      
    ],
  
  	'supporturl' => env('DEROPAY_LIVE_SUPPORT_URL', ''),
  	'successurl' => env('DEROPAY_PAYMENT_SUCCESS_REDIRECT_URL', ''),
    
  	'wallet' => env('DEROPAY_WALLET_ENDPOINT', 'http://127.0.0.1:20209/'),
  	'daemon' => env('DEROPAY_DAEMON_ENDPOINT', 'http://127.0.0.1:20206/'),
	
    'confirmations' => env('DEROPAY_WALLET_REQUIRED_CONFIRMATIONS', 20),
    
    
];
