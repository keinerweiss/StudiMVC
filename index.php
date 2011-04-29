<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once('config.php');
require_once('classes/Helpers.php');
/**
 * URL Aufbau: 
 * index.php/controllerName/aktionName
 */

//$path = "/user/login";
if(empty($_SERVER['PATH_INFO'])) {
	$_SERVER['PATH_INFO'] = '/index/index/index';
}
$path = $_SERVER['PATH_INFO'];

/**
 * Pfad zerlegen nach /
 * TODO: Alle Teile vorhanden?
 */
$pathParts = preg_split('#/#',$path,-1,PREG_SPLIT_NO_EMPTY);

/**
 * Modul, Controller und Action aus URL mit Standardwert
 */
$moduleName =     isset($pathParts[0]) ? $pathParts[0] : 'index';
$controllerName = isset($pathParts[1]) ? $pathParts[1] : 'index';
$actionName =     isset($pathParts[2]) ? $pathParts[2] : 'index';

/**
 * Erster Buchstabe der Controller-Klasse ist groß
 * Ein Controller endet immer auf *Controller
 * Eine Action endet immer auf *Action
 */
$controllerClass = ucfirst($controllerName).'Controller';
$actionMethod = $actionName.'Action';

/**
 * Controller einbinden
 * TODO: file_exists?
 */
$controllerInclude = "modules/$moduleName/controllers/$controllerClass.php";

if(strpos($moduleName.$controllerClass,'/')!== false) {
	die("request denied");
}
if(!is_dir("modules/$moduleName")) {
	die("module $moduleName unknown");
}
if(!is_file($controllerInclude)) {
	die("controller $controllerClass unknown");
}

/**
 * Datenbankverbindung herstellen
 */
if(Config::get('db.enabled')) {
	$dbConf = Config::get('db');
	MySQL::connect($dbConf['host'],$dbConf['user'],$dbConf['password'],$dbConf['database']);
}

require_once($controllerInclude);

if(!class_exists($controllerClass)) {
	die("controller class $controllerClass not declared");
}

$controller = new $controllerClass();
if(! $controller instanceof Controller) {
	die('Kein richtiger Controller');
}

if(!method_exists($controllerClass, $actionMethod)) {
	die("action $actionMethod not available");
}


/**
 * Action dynamisch aufrufen, view auslesen pfad erzeugen
 * TODO: method_exists? file_exists? View angegeben?
 */
$controller->$actionMethod();

$viewName = $controller->getView();
if(!$viewName) {
	$viewName = $actionName;
}
$viewInclude = "modules/$moduleName/views/$viewName.php";

/**
 * Ausgabeformat Schalter
 * ?format=json -> JSON Ausgabe
 * ?format=xml -> XML Ausgabe
 * ?format= -> HTML Ausgabe
 */
settype($_GET['format'],'string');
if($_GET['format'] == 'json') {

	echo json_encode($controller->getData());

} else {
	if($controller->redirect) {
		list($toModule,$toController,$toAction,$toParams) = each($controller->redirect[0]);
		if(count($r)>=3) {
			H::rediect($toModule,$toController,$toAction,@$toParams);
		}
		exit;		
	}
	if(!is_file($viewInclude)) {
		die('view unknown');
	}
	
	/**
	 * Jeder Array-Key wird in eine Variable umgeschrieben
	 * $data['name'] -> $name
	 */
	extract($controller->getData());
	/**
	 * Inhaltsbereich erzeugen und zwischenspeichern
	 */
	ob_start();
	include($viewInclude);
	$CONTENT = ob_get_clean();
	/**
	 * Einbinden des Basis-Layouts, darin: Ausgabe des Inhaltsbereichs
	 */
	include("modules/$moduleName/views/template.php");
}