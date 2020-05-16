<?php

function printInfo($message, $connection, $errors = null) {
	$sql = 'select name, price from engines where id in(?, ?)';
	$parameters = [2, 3];
	$statement = sqlsrv_query($connection, $sql, $parameters);
	$result = [];
	while ($row = sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC)) {
		$result[] = $row;
	}
	echo '<div style="display: inline-block;">' . $message . '<pre>';
	var_dump($result);
	echo '</pre>';
	if ($errors) {
		echo $errors[0]['message'];
	} else {
		echo '&nbsp;';
	}
	echo '</div>';
}

// Підключення до БД
$name = 'LENOVOB560';
$options = [
	'Database' => 'vwdb',
	'CharacterSet' => 'UTF-8'
];
$connection = sqlsrv_connect($name, $options);

// Початок транзакції
printInfo('Before: ', $connection);
if(!sqlsrv_begin_transaction($connection)) {
	echo 'Unexpected error!';
	exit;
}
$errors = null;

// Виконання операцій
$sql = 'update engines set price = ? where id = ?';
$statementFirst = sqlsrv_query($connection, $sql, [1999, 2]);
//$sql = 'update engines set price = ? where id = ?';
$sql = 'update engins set price = ? where id = ?';
$statementSecond = sqlsrv_query($connection, $sql, [2999, 3]);
$errors = sqlsrv_errors();

// Обробка результату транзакції
if ($statementFirst && $statementSecond) {
	sqlsrv_commit($connection);
	printInfo('Commited! ', $connection, $errors);
} else {
	sqlsrv_rollback($connection);
	printInfo('Rolled back! ', $connection, $errors);
}

sqlsrv_close($connection);