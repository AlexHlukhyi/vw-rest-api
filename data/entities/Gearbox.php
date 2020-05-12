<?php

namespace data\entities;

use JsonSerializable;

class Gearbox implements JsonSerializable {
	private $id;
	private $name;
	private $type;
	public function __construct($id, $name = null, $type = null) {
		$this->id = $id;
		$this->name = $name;
		$this->type = $type;
	}
	public function getId() {
		return $this->id;
	}
	public function getName() {
		return $this->name;
	}
	public function getType() {
		return $this->type;
	}
	public function jsonSerialize() {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'type' => $this->type
		];
	}
}