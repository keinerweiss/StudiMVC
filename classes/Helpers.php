<?php
/**
 * Helpers for common tasks
 */
class Helpers {
	
	/**
	 * Encode destructive contents in HTML
	 */
	static function h($string) {
		return htmlspecialchars($string);
	}
	
	/**
	 * Encode destructive content in URL
	 */
	static function u($string) {
		return rawurlencode($string);
	}
	
	/**
	 * Create a valid link within Framework
	 */
	static function link($module,$controller,$action,$param='') {
		if($param) {
			return Config::get('relDir')."/index.php/$module/$controller/$action?$param";
		}
		return Config::get('relDir')."/index.php/$module/$controller/$action";
	}
	
	/**
	 * Redirect to another page.
	 * @see link()
	 */
	static function redirect($module, $controller,$action,$param='') {
		header('Location: '.self::link($module,$controller,$action,$param));
		exit;
	}

}

/**
 * Shortcut for easier use.
 * E.g. 
 * echo H::link('admin','users','get');
 */
class H extends Helpers {
}
