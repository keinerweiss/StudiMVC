<?php
/**
 * Class to hold configurations
 */
class Config {
	/**
	 * @var $setup array of config options
	 */
	protected static $setup = array();
	
	/**
	 * Initialize giving all configuration options as array
	 * @param $setup Array of configuration options
	 */
	function init($setup) {
		self::$setup = $setup;
	}
	
	/**
	 * Return value of specific key in array.
	 * 
	 * Use dot (.) to separate array levels.
	 * E.g.
	 * Config::get('db.user')
	 * will return $setup['db']['user']
	 * 
	 * @param $key Key to find in array
	 * @return mixed. value behind array key
	 */
	static function get($key) {
		$parts = explode('.',$key);
		$first = array_shift($parts);
		if(!isset(self::$setup[$first])) {
			return '';
		}
		/**
		 * Repeat cutting to the next dot and diving into the array
		 * until there are no more dots.
		 */
		$ret = self::$setup[$first];
		while(count($parts) > 0) {
			$first = array_shift($parts);
			if(!isset($ret[$first])) {
				return '';
			}
			$ret = $ret[$first];
		}
		return $ret;
	}
}
