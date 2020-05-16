<?php

include __DIR__ . '/../data/autorun.php';

use data\App;
use data\entities\Complectation;
use data\entities\Engine;
use data\entities\Gearbox;
use data\entities\Model;

$app = new App();

switch ($_GET['action']) {
	case 'getComplectations': {
		$complectations = $app->getComplectations();
		$response = [
			'quantity' => $complectations[0][0]['quantity'],
			'complectations' => $complectations[1]
		];
		break;
	}
	case 'getComplectation' : {
		$complectation = $app->getComplectation((int)$_GET['complectation-id']);
		$response = ['complectation' => $complectation];
		break;
	}
	case 'getModels' : {
		$models = $app->getModels();
		$response = ['models' => $models];
		break;
	}
	case 'getEngines' : {
		$engines = $app->getEngines();
		$response = ['engines' => $engines];
		break;
	}
	case 'getGearboxes' : {
		$gearboxes = $app->getGearboxes();
		$response = ['gearboxes' => $gearboxes];
		break;
	}
	case 'insertComplectation' : {
		$complectation = new Complectation(
			0,
			$_POST['complectation-name'],
			new Model((int)$_POST['model-id']),
			new Engine((int)$_POST['engine-id']),
			new Gearbox((int)$_POST['gearbox-id'])
		);
		$response = ['inserted' => $app->insertComplectation($complectation)];
		break;
	}
	case 'updateComplectation' : {
		$complectation = new Complectation(
			(int)$_POST['complectation-id'],
			$_POST['complectation-name']
		);
		$response = ['updated' => $app->updateComplectation($complectation)];
		break;
	}
	case 'deleteComplectation' : {
		$response = ['deleted' => $app->deleteComplectation((int)$_POST['complectation-id'])];
		break;
	}
 	default : {

	}
}

header('Access-Control-Allow-Origin: *');
header('Content-type:application/json');
header('Accept-Language: *');

echo json_encode($response);