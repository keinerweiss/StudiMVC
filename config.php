<?php

require_once('classes/Config.php');
require_once('classes/MySQL.php');
require_once('classes/Controller.php');

// use $dirFS = '/path/to/here'; if paths are not correct
$dirFS = str_replace("\\",'/',dirname(__FILE__));
$dirWeb = str_replace($_SERVER['DOCUMENT_ROOT'],'',$dirFS);

Config::init(array(
	'projectDir' => $dirFS,
	'relDir' => $dirWeb,
	'db' => array(
		'enabled' => true,
		'host' => 'localhost',
		'user' =>  'root',
		'password' => '',
		'database' => 'database_name'
	),	
	'modules' => array(
		'module' => array(),
	),
	
));
