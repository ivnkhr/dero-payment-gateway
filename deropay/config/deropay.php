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
	
  	'support' => env('DEROPAY_LIVE_SUPPORT_URL', ''),
  	
  	'wallet' => env('DEROPAY_WALLET_ENDPOINT', 'http://127.0.0.1:30307/'),
  	
  	'daemon' => env('DEROPAY_DAEMON_ENDPOINT', 'http://127.0.0.1:30306/'),
	
];
