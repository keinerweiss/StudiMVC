<?php
/**
 * Include models if required
 */

require_once(__DIR__.'/../models/Example.php');

/**
 * Controller class for ...
 *
 * What's this controller for?
 */
class IndexController extends Controller {

	/**
	 * Default action "index"
	 *
	 * What does it handle?
	 */	
	function indexAction() {
		// create an instance of the model
		$exampleHandler = new Example();

		// use the model to fetch or calculate data
		$magicNumber = $exampleHandler->getMagicNumber();

		// collect data for the view.
		$this->data['magic'] = $magicNumber;

		// Check for errors and collect them
		if($magicNumber == 42) {
			$this->errors[] = 'This is not the answer!';
		}

		// validate URL or form parameters
		if(!isset($_GET['test'])) {
			$this->errors[] = 'Not in Testmodus!';
		}

		// Maybe jump (redirect) to another page
		if(isset($_GET['redirect'])) {
			$this->redirect = array('index','index','test');
		}

	}

	function testAction() {
		// Since nothing is in here, the plain view will be shown
	}
	
}
