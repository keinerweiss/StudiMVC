<?php
/**
 * Helpers for common tasks
 */
class Helpers {
	
	/**
	 * Encode destructive contents in HTML
	 * 
	 * @param string Text to encode
	 * @return string htmlspecialchar'd string
	 */
	static function h($string) {
		return htmlspecialchars($string);
	}
	
	/**
	 * Encode destructive content in URL
	 * 
	 * @param string Text to encode
	 * @return string rawurlencoded string
	 */
	static function u($string) {
		return rawurlencode($string);
	}
	
	/**
	 * Create a valid link within Framework
	 * 
	 * @param string Module
	 * @param string Controller
	 * @param string Action
	 * @param string GET Paramaters without "?"
	 * @return string Link to Module/Controller/Action?Param
	 */
	static function link($module,$controller,$action,$param='') {
		if($param) {
			return Config::get('relDir')."/index.php/$module/$controller/$action?$param";
		}
		return Config::get('relDir')."/index.php/$module/$controller/$action";
	}
	
	/**
	 * Redirect to another page. 
	 * 
	 * Adds HTTP header and stops script execution.
	 * 
	 * @param string Module
	 * @param string Controller
	 * @param string Action
	 * @param string GET Paramaters without "?"
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
