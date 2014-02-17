<?php

/*********************************************
 * PHP Game Framework            			 *
 * Published under MIT License   			 *
 * Copyright (c) 2013-2014 Third Planet Team *
 *********************************************/

namespace api\tools;

class GameplayElement {
	protected $data = array();

	protected function addData($var, $data) {
		$this->data[$var] = $data;
	}

	protected function getData($var) {
		if(isset($this->data[$var])) {
			return $this->data[$var];
		}

		return null;
	}

	protected function deleteData($var) {
		if(isset($this->data[$var])) {
			unset($this->data[$var]);
		}
	}

	protected function existsData($var) {
		return isset($this->data[$var]);
	}


	public function __get($name) {
		return $this->getData($name);
	}

	public function __set($name, $value) {
		$this->addData($name, $value);
		return $this;
	}

	public function __unset($name) {
		$this->deleteData($name);
		return $this;
	}

	public function __isset($name) {
		return $this->existsData($name);
	}

}