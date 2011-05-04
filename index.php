<?php
/**
 * Bootstrap.
 */
 
ini_set('display_errors','On');
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
 */
$pathParts = preg_split('/\//',$path,-1,PREG_SPLIT_NO_EMPTY);

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
 * Controller einbinden, Namenskonvention prüfen.
 */
$controllerInclude = "modules/$moduleName/controllers/$controllerClass.php";

/**
 * Weder Modul noch Controller dürfen Verzeichnistrenner (/) beinhalten.
 */
if(strpos($moduleName.$controllerClass,'/')!== false) {
	die("request denied");
}
/**
 * Modulverzeichnis muss existieren
 */
if(!is_dir("modules/$moduleName")) {
	die("module $moduleName unknown");
}
/**
 * Die Controller Datei muss existieren
 */
if(!is_file($controllerInclude)) {
	die("controller $controllerClass unknown");
}

require_once($controllerInclude);

/**
 * Nach dem einbinden muss enspr. der Konvention eine Klasse existieren
 */
if(!class_exists($controllerClass)) {
	die("controller class $controllerClass not declared");
}

/**
 * Controller müssen alle von der Basisklasse Controllen abgeleitet sein
 */
$controller = new $controllerClass();
if(! $controller instanceof Controller) {
	die('Not a real Controller');
}

/**
 * Die angegebene Action muss per Konvention enthalten sein
 */
if(!method_exists($controllerClass, $actionMethod)) {
	die("Action $actionMethod not available");
}

/**
 * Datenbankverbindung herstellen
 */
if(Config::get('db.enabled')) {
	$dbConf = Config::get('db');
	MySQL::connect($dbConf['host'],$dbConf['user'],$dbConf['password'],$dbConf['database']);
}

/**
 * Action dynamisch aufrufen, view auslesen pfad erzeugen
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
	/**
	 * Prüfen, ob der Controller einen Redirect veranlasst hat, ggf.
	 * umlenken.
	 */
	if($controller->redirect) {
		
		list($toModule,$toController,$toAction) = $controller->redirect;
		
		if(count($controller->redirect)>=3) {
			$toParams = isset($controller->redirect[3]) ? $controller->redirect[3] : '';
			H::redirect($toModule,$toController,$toAction,@$toParams);
		}
		exit;
	}
	/**
	 * Prüfen ob die View-Datei existiert
	 */
	if(!is_file($viewInclude)) {
		die('view unknown');
	}
	
	/**
	 * Jeder Array-Key wird in eine Variable umgeschrieben
	 * $data['name'] -> $name
	 */
	extract($controller->getData());
	$errors = $controller->getErrors();
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
