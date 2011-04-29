<?php
class Helpers {
	static function h($string) {
		return htmlspecialchars($string);
	}
	
	static function u($string) {
		return rawurlencode($string);
	}
	
	static function link($module,$controller,$action,$param='') {
		if($param) {
			return Config::get('relDir')."/index.php/$module/$controller/$action?$param";
		}
		return Config::get('relDir')."/index.php/$module/$controller/$action";
	}
	
	static function redirect($module, $controller,$action,$param='') {
		header('Location: '.self::link($module,$controller,$action,$param));
		exit;
	}

}

class H extends Helpers {
}