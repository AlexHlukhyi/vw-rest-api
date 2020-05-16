<?php

namespace data;

use data\entities\Complectation;
use data\entities\Engine;
use data\entities\Gearbox;
use data\entities\Model;

class App {
	private $db;

	public function __construct() {
		$this->db = new DB();
	}

	public function getModels() {
		$result = [];
		$sql = 'select id, name from models';
		if ($models = $this->db->makeQuery($sql)) {
			foreach ($models as $model) {
				$result[] = new Model($model['id'], $model['name']);
			}
			return $result;
		} else {
			return false;
		}
	}
	public function getEngines() {
		$result = [];
		$sql = 'select id, name from engines';
		if ($engines = $this->db->makeQuery($sql)) {
			foreach ($engines as $engine) {
				$result[] = new Engine($engine['id'], $engine['name']);
			}
			return $result;
		} else {
			return false;
		}
	}
	public function getGearboxes() {
		$result = [];
		$sql = 'select id, name, type from gearboxes';
		if ($gearboxes = $this->db->makeQuery($sql)) {
			foreach ($gearboxes as $gearbox) {
				$result[] = new Gearbox($gearbox['id'], $gearbox['name'], $gearbox['type']);
			}
			return $result;
		} else {
			return false;
		}
	}
	public function getComplectations() {
		$result = $this->db->runSP('getComplectations');
		if (!$result) {
			return $result;
		}
		$quantity = $result[0];
		if (!$quantity || !$quantity[0]) {
			return false;
		}
		$rows = $result[1];
		$complectations = [];
		foreach ($rows as $row) {
			$complectations[] = new Complectation(
				$row['complectation_id'], $row['complectation_name'],
				new Model($row['model_id'], $row['model_name']),
				new Engine($row['engine_id'], $row['engine_name']),
				new Gearbox($row['gearbox_id'], $row['gearbox_name'], $row['gearbox_type'])
			);
		}
		return [$quantity, $complectations];
	}
	public function getComplectation($complectationId) {
		$result = $this->db->runSP('getComplectation', [
			[$complectationId, 'in']
		]);
		if (!$result || !$result[0]) {
			return false;
		} else {
			$complectation = new Complectation(
				$result[0][0]['complectation_id'],
				$result[0][0]['complectation_name']
			);
			return $complectation;
		}
	}
	public function deleteComplectation($complectationId) {
		return $this->db->runSP('deleteComplectation', [
			[$complectationId, 'in']
		]);
	}
	public function updateComplectation(Complectation $complectation) {
		$status = 0;
		$this->db->runSP('editComplectation', [
			[$complectation->getId(), 'in'],
			[$complectation->getName(), 'in'],
			[&$status, 'out']
		]);
		return (bool)$status;
	}
	public function insertComplectation(Complectation $complectation) {
		return $this->db->runSP('insertComplectation', [
			[$complectation->getName(), 'in'],
			[$complectation->getModel()->getId(), 'in'],
			[$complectation->getEngine()->getId(), 'in'],
			[$complectation->getGearbox()->getId(), 'in'],
		]);
	}
}