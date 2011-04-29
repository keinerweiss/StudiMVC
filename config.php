<?php

require_once('classes/Config.php');
require_once('classes/MySQL.php');
require_once('classes/Controller.php');

$dirFS = dirname(__FILE__);
$dirWeb = str_replace($_SERVER['DOCUMENT_ROOT'],'',$dirFS);

Config::init(array(
	'projectDir' => $dirFS,
	'relDir' => $dirWeb,
    
	'db' => array(
		'enabled' => false,
		'host' => 'localhost',
		'user' =>  'root',
		'password' => '',
		'database' => 'project_db'
	),	
	'modules' => array(
		'module' => array(),
	),
	
));
