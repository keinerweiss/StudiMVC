<?php
class Config {
	protected static $setup = array();
	
	function init($setup) {
		self::$setup = $setup;
	}
	
	static function get($key) {
		$parts = explode('.',$key);
		$first = array_shift($parts);
		if(!isset(self::$setup[$first])) {
			return '';
		}
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