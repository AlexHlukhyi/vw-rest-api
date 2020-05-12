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
		$result = [];
		$sql = 'select m.id model_id, m.name model_name, 
       				e.id engine_id, e.name engine_name, 
					g.id gearbox_id, g.name gearbox_name, g.type gearbox_type, 
       				c.id complectation_id, c.name complectation_name
				from complectations c
					inner join models m on m.id = c.model_id
					inner join engines e on e.id = c.engine_id
					inner join gearboxes g on g.id = gearbox_id';
		if ($complectations = $this->db->makeQuery($sql)) {
			foreach ($complectations as $complectation) {
				$result[] = new Complectation(
					$complectation['complectation_id'], $complectation['complectation_name'],
					new Model($complectation['model_id'], $complectation['model_name']),
					new Engine($complectation['engine_id'], $complectation['engine_name']),
					new Gearbox($complectation['gearbox_id'], $complectation['gearbox_name'], $complectation['gearbox_type'])
				);
			}
			return $result;
		} else {
			return false;
		}
	}
	public function getComplectation($complectationId) {
		$sql = 'select m.id model_id, m.name model_name, 
       				e.id engine_id, e.name engine_name, 
					g.id gearbox_id, g.name gearbox_name, g.type gearbox_type, 
       				c.id complectation_id, c.name complectation_name
				from complectations c
					inner join models m on m.id = c.model_id
					inner join engines e on e.id = c.engine_id
					inner join gearboxes g on g.id = gearbox_id
				where c.id = ' . $complectationId;
		$complectation = $this->db->makeQuery($sql);
		if (!$complectation || !$complectation[0]) {
			return false;
		} else {
			$result = new Complectation(
				$complectation[0]['complectation_id'], $complectation[0]['complectation_name'],
				new Model($complectation[0]['model_id'], $complectation[0]['model_name']),
				new Engine($complectation[0]['engine_id'], $complectation[0]['engine_name']),
				new Gearbox($complectation[0]['gearbox_id'], $complectation[0]['gearbox_name'], $complectation[0]['gearbox_type'])
			);
			return $result;
		}
	}
	public function deleteComplectation($complectationId) {
		$sql = 'delete from complectations where id = ' . $complectationId;
		return $this->db->makeQuery($sql);
	}
	public function updateComplectation(Complectation $complectation) {
		$sql = 'update complectations set  name = \''
					. $complectation->getName()
				. '\' where id = ' . $complectation->getId();
		return $this->db->makeQuery($sql);
	}
	public function insertComplectation(Complectation $complectation) {
		$sql = 'insert into complectations(name, model_id, engine_id, gearbox_id) values(\''
					. $complectation->getName() . '\','
					. $complectation->getModel()->getId() . ','
					. $complectation->getEngine()->getId() . ','
					. $complectation->getGearbox()->getId() . ')';
		return $this->db->makeQuery($sql);
	}
}