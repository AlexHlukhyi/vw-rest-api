<?php

require_once __DIR__ . '/data/autorun.php';

//spl_autoload_register(function ($className) {
//	$namespace = str_replace("\\","/",__NAMESPACE__);
//	$url = '../'. (empty($namespace)?"":$namespace . "/") . $className . '.php';
//	if(file_exists($url)) {
//		require_once($url);
//		return;
//	}
//});

use data\entities\Complectation;

$complectation = new Complectation(11);
$complectation->readFromDB();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie-edge">
	<title>Створені автомобілі</title>
</head>
<body>
	<h2>Комплектація <?= $complectation->getName()?></h2>
	<h3>Двигун <?= $complectation->getEngine()->getName()?></h3>
	<h3>Коробка передач <?= $complectation->getGearbox()->getType()?> <?= $complectation->getGearbox()->getName()?></h3>
	<h4>Збережені автомобілі цієї комплектації</h4>
    <table border="1">
		<thead>
			<tr>
				<th>№</th>
                <th>ID</th>
                <th>Модель</th>
                <th>Колір</th>
				<th>Дата</th>
			</tr>
		</thead>
		<?php foreach ($complectation->getCars() as $i => $car): ?>
			<tr>
				<td><?= $i+1?></td>
                <td><?= $car->getId()?></td>
                <td><?= $car->getComplectation()->getModel()->getName()?></td>
                <td><?= $car->getColor()?></td>
				<td><?= $car->getDate()?></td>
			</tr>
		<?php endforeach; ?>
	</table>
</body>
</html>
