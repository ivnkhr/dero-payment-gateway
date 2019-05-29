<?php

$data = @file_get_contents('supporters.txt');

$records = explode('[:::]',$data);

foreach($records as $k=>$v){
	$records[$k] = explode('[PPP]',$v);
	$records[$k][1] = @$records[$k][1] / 1000000000000;
}

unset($records[0]);
header('Content-Type: application/json');
echo json_encode(array_values($records));