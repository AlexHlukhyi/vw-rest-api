<?php

include __DIR__ . '/../data/autorun.php';
use data\entities\Complectation;

if ($_GET['action'] == 'getComplectation') {
	$complectation = new Complectation((int)$_GET['complectationId']);
	$complectation->readFromDB();
	$data = ['complectation' => $complectation];
} else {
	$data = ['error' => 'bad request'];
}

header('Access-Control-Allow-Origin: *');
header('Content-type:application/json');
header('Accept-Language: *');

echo json_encode($data);