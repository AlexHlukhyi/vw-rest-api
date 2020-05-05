<?php

namespace data\entities;

use data\DB;
use DateTime;
use JsonSerializable;

class Car implements JsonSerializable {
	private $id;
	private $complectation;
	private $color;
	private $date;
	public function __construct($id, Complectation $complectation, $color, DateTime $date) {
		$this->id = $id;
		$this->complectation = $complectation;
		$this->color = $color;
		$this->date = $date;
	}
	public function getId() {
		return $this->id;
	}
	public function getComplectation() {
		return $this->complectation;
	}
	public function getDate() {
		return $this->date->format('H:i d.m.Y');
	}
	public function getColor() {
		return $this->color;
	}
	public function jsonSerialize() {
		return [
			'id' => $this->id,
			'color' => $this->color,
			'date' => $this->date
		];
	}
}