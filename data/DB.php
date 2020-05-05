<?php

namespace data;

class DB {
	private $errorText = '';
	private $connection;
	function getErrorText() {
		return $this->errorText;
	}
	function connect() {
		$this->errorText = '';
		try {
			$name = 'LENOVOB560';
			$options = [
				'Database' => 'vwdb',
				'CharacterSet' => 'UTF-8'
			];
			$this->connection = sqlsrv_connect($name, $options);
			if ($this->connection == false) {
				$this->errorText = sqlsrv_errors();
				return false;
			}
			return true;
		} catch (Exception $ex) {
			$this->errorText = 'Unexpected Exception!';
			return false;
		}
	}
	function disconnect() {
		sqlsrv_close($this->connection);
	}
	function makeQuery($sql, $flagDisconnect = true) {
		$this->errorText = '';
		$result = [];
		try {
			if ($this->connection || $this->connect()) {
				$statement = sqlsrv_query($this->connection, $sql);
				if (!$statement) {
					$this->errorText = sqlsrv_errors();
					return false;
				}
				while ($row = sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC)) {
					$result[] = $row;
				}
				sqlsrv_free_stmt($statement);
				return $result;
			}
		} catch (Exception $ex) {
			$this->errorText = 'Unexpected Exception!';
			return false;
		}
	}
}