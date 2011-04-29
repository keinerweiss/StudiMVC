<?php
/**
 * Base class for all Controllers.
 * 
 * Contains the basic controller functionality like data - and error 
 * collection, view selection and redirecting.
 */
class Controller {

	/**
	 * @var string View filename without file extension
	 */
	protected $view = '';
	
	/**
	 * @var array Container for business data; for use in view
	 */
	protected $data = array();
	
	/**
	 * @var array Container for error messages
	 */
	protected $errors = array();
	
	/**
	 * @var array Module, Controller, Action, Params to jump to
	 */
	public $redirect = null;

	/**
	 * Return the view filename if set.
	 * 
	 * @return string View filename without extension. Empty if none.
	 */
	function getView() {
		return $this->view;
	}

	/**
	 * Return collected business data
	 * 
	 * @return array Business data
	 */
	function getData() {
		return $this->data;
	}
	
	/**
	 * Return collected error messages
	 * 
	 * @return array Error messages
	 */
	function getErrors() {
		return $this->errors;
	}

}
