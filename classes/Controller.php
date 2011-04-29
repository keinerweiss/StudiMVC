<?php
class Controller {

	protected $view = '';
	protected $data = array();
	protected $errors = array();
	public $redirect = null;

	function getView() {
		return $this->view;
	}

	function getData() {
		return $this->data;
	}
	
	function getErrors() {
		return $this->errors;
	}

}