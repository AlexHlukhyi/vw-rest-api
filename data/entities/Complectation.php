<?php

namespace data\entities;

use data\DB;
use JsonSerializable;

class Complectation implements JsonSerializable{
	private $id;
	private $name;
	private $model;
	private $engine;
	private $gearbox;
	private $cars = [];
	public function __construct($id) {
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}
	public function getName() {
		return $this->name;
	}
	public function getModel() {
		return $this->model;
	}
	public function getEngine() {
		return $this->engine;
	}
	public function getGearbox() {
		return $this->gearbox;
	}
	public function getCars() {
		return $this->cars;
	}
	function readFromDB() {
		$db = new DB();
		$sql = 'select m.id model_id, m.name model_name, 
       				e.id engine_id, e.name engine_name, 
					g.id gearbox_id, g.name gearbox_name, g.type gearbox_type, 
       				c.id complectation_id, c.name complectation_name
				from complectations c
					inner join models m on m.id = c.model_id
					inner join engines e on e.id = c.engine_id
					inner join gearboxes g on g.id = gearbox_id
				where c.id = ' . $this->id;
		$complectation = $db->makeQuery($sql);
		if (!$complectation || !$complectation[0]) {
			return false;
		}
		$this->name = $complectation[0]['complectation_name'];
		$this->model = new Model($complectation[0]['model_id'], $complectation[0]['model_name']);
		$this->engine = new Engine($complectation[0]['engine_id'], $complectation[0]['engine_name']);
		$this->gearbox = new Gearbox($complectation[0]['gearbox_id'], $complectation[0]['gearbox_name'], $complectation[0]['gearbox_type']);
		$sql = 'select cars.id car_id, cars.updated_at car_date, colors.name car_color
					from cars 
					inner join colors on colors.id = cars.color_id
				where complectation_id = ' . $this->id;
		if ($cars = $db->makeQuery($sql)) {
			foreach ($cars as $car) {
				$this->cars[] = new Car(
					$car['car_id'], $this, $car['car_color'], $car['car_date']
				);
			}
		}
		return true;
	}
	public function jsonSerialize() {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'model' => $this->model,
			'engine' => $this->engine,
			'gearbox' => $this->gearbox,
			'cars' => $this->cars
		];
	}
}