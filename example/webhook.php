<?php

require 'config.php';

global $api_secret;

//@file_put_contents('debug', print_r($_POST, true));

if( isset($_POST['id']) && ( hash('sha256', $_POST['id'].$api_secret) == $_POST['invoice_signature'] ) && $_POST['status'] == 3){ //Check if signature is valid and invoice status is PAID
	//@file_put_contents('debug_succ', print_r($_POST, true));
	$my_file = 'supporters.txt';
	$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
	$nick = 'Anon';
	if( isset($_POST['webhook_data']) && isset($_POST['webhook_data']['customer_nick']) ) $nick = $_POST['webhook_data']['customer_nick'];
	$new_data = "[:::]".''.$nick.'[PPP]'.@$_POST['price'];
	//$new_data = print_r($_POST, TRUE);
	fwrite($handle, $new_data);
}