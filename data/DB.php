<?php

namespace data;

use http\Exception;

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
	function runSP($name, $params = []) {
		try {
			if ($this->connection || $this->connect()) {
				$result = [];
				$str = str_repeat("?, ", count($params));
				if (strlen($str) > 0) {
					$str = substr($str, 0, strlen($str) - 2);
				}
				$sql = 'exec ' . $name . ' ' . $str;
				foreach ($params as $key => $param) {
					if (isset($param[1]) && $param[1] == 'out') {
						$params[$key][1] = SQLSRV_PARAM_OUT;
					} else {
						$params[$key][1] = SQLSRV_PARAM_IN;
					}
				}
				$statement = sqlsrv_prepare($this->connection, $sql, $params);
				sqlsrv_execute($statement);
				while ($row = sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC)) {
					$oneResult[] = $row;
				}
				$result[] = $oneResult;
				while (sqlsrv_next_result($statement)) {
					$oneResult = [];
					while ($row = sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC)) {
						$oneResult[] = $row;
					}
					$result[] = $oneResult;
				}
				return $result;
			}
		} catch (Exception $ex) {
			$this->errorText = 'Unexpected Exception!';
			return false;
		}
	}
}