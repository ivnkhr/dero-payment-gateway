<?php

require('config.php');

$data = array(
    'name' => 'TIP',
    'desc' => @$_GET['val'] . ' DERO',
	'ttl' => 60,
	'price' => (int) @$_GET['val'] * 1000000000000,
	'webhook_data' => [
		'customer_nick' => @$_GET['nick'],
	]
);
 
$payload = json_encode($data);
 
// Prepare new cURL resource
$ch = curl_init($api_endpoint.'/api/invoice/generate');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
 
// Set HTTP Header for POST request 
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($payload),
	'Api-Secret: ' . $api_secret
	)
);
 
// Submit the POST request
$result = curl_exec($ch);
 
$result = json_decode($result);

if(isset($result->id)){
	echo $result->payment_url;
}else{
	header("HTTP/1.0 404 Not Found");
}
 
// Close cURL session handle
curl_close($ch);