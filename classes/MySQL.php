<?php
class MySQL {
	protected static $resource = null;
	
	static function connect($host,$user,$pass,$db) {
		$db = new mysqli($host,$user,$pass,$db);
		self::$resource = $db;
		// TODO: error handling
	}
	
	static function query($sql,$values=array(),$className=null) {
		foreach($values as $k=>$v) {
			$sql = preg_replace('/\?/', "'".self::$resource->real_escape_string($v)."'", $sql, 1);
		}
		$res = self::$resource->query($sql);
		$rows = array();
		if(!$className) {
			while($row = $res->fetch_object()) {
				$rows[] = $row;
			}
		} else {
			while($row = $res->fetch_object($className)) {
				$rows[] = $row;
			}
		}
		return $rows;
	}
	
	static function execute($sql,$values,$types=null) {
		$stmt = self::prepareCommand($sql,$values,$types);
		if(strpos(strtolower(trim($sql)),'insert')===0) {
			return $stmt->insert_id;
		}
		return $stmt->affected_rows;
	}
	
	static function prepareCommand($sql,$values=array(),$types=null) {
		if(self::$resource) {
			$stmt = self::$resource->prepare($sql);
			if($stmt) {
				if($types === null) {
					$types = str_repeat('s',count($values)); 
				}
				if(strlen($types)) {
				  $args = $values;
				  array_unshift($args,$types);
				  call_user_method_array("bind_param", $stmt, $args);
				}
				
			} else {
				throw new Exception('MYSQL: Statement could not be created');
			}
			if($stmt->execute()) {
				return $stmt;
			}
		}
		
		return null;
	}
	
}