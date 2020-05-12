<?php

namespace data\entities;

use JsonSerializable;

class Complectation implements JsonSerializable{
	private $id;
	private $name;
	private $model;
	private $engine;
	private $gearbox;
	public function __construct($id, $name, Model $model = null, Engine $engine = null, Gearbox $gearbox = null) {
		$this->id = $id;
		$this->name = $name;
		$this->model = $model;
		$this->engine = $engine;
		$this->gearbox = $gearbox;
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
	public function jsonSerialize() {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'model' => $this->model,
			'engine' => $this->engine,
			'gearbox' => $this->gearbox
		];
	}

}