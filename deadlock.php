<?php
// Підключення до БД
$name = 'LENOVOB560';
$options = [
	'Database' => 'vwdb',
	'CharacterSet' => 'UTF-8'
];
$connection = sqlsrv_connect($name, $options);

$data['transaction'] = $_GET['number'];

function printInfo($message, $connection) {
	global $data;
	$sql = 'select name, price from engines where id in(?, ?)';
	$parameters = [2, 3];
	$result = [];
	if ($statement = sqlsrv_query($connection, $sql, $parameters)) {
		while ($row = sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC)) {
			$result[] = $row;
		}
	} else {
		$result['error'] = sqlsrv_errors();
	}
	$data[$message] = $result;
}

printInfo('1. Before: ', $connection);
if (!sqlsrv_begin_transaction($connection)) {
	$data['error'] = 'Помилка при запуску транзакції!';
} else {
	if ($_GET['number'] == 1) {
		$sql = 'update engines set price = 1599 where id = 2';
	} else {
		$sql = 'update engines set price = 2599 where id = 3';
	}
	$statement = sqlsrv_query($connection, $sql);
	if (!$statement) {
		$data['update_error'] = sqlsrv_errors();
	}
	printInfo('2. Inside: ', $connection);
}

sqlsrv_commit($connection);
printInfo('3. After: ', $connection);

if ($statement) {
	sqlsrv_free_stmt($statement);
}
sqlsrv_close($connection);

header('Access-Control-Allow-Origin: *');
header('Content-type:application/json');
header('Accept-Language: *');

echo json_encode($data);