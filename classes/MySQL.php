<?php
/**
 * MySQLi simplification class
 */
class MySQL {
	
	/**
	 * @var resource Database connection resource object
	 */
	protected static $resource = null;
	
	/**
	 * Connect to the database
	 * 
	 * @param string Hostname
	 * @param string Username
	 * @param string Password
	 * @param string Database name
	 * @return boolean true on success
	 */
	static function connect($host,$user,$pass,$db) {
		$db = new mysqli($host,$user,$pass,$db);
		self::$resource = $db;
		return self::$resource !== null;
	}
	
	/**
	 * Query the database (perform a select statement)
	 * 
	 * Mimics the behaviour of binding parameters for simpler use.
	 * 
	 * Example:
	 * $rows = $db->query("SELECT * FROM users where id=? and email=?",
	 * 	 array(5, 'test@example.com'));
	 * 
	 * @param string SQL statement. Must be a select or similar.
	 * @param array Bind values. One for each "?"
	 * @param string Class name of the result objects.
	 * @return array of objects
	 */
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
	
	/**
	 * Execute a command (other then select, e.g. DELETE OR UPDATE)
	 * 
	 * @param string SQL statement. Must be a select or similar.
	 * @param array Bind values. One for each "?"
	 * @param string Charcter list of types. E.g. "ssds"
	 * @see prepareCommand()
	 * @return integer Affected rows
	 */
	static function execute($sql,$values,$types=null) {
		$stmt = self::prepareCommand($sql,$values,$types);
		if(strpos(strtolower(trim($sql)),'insert')===0) {
			return $stmt->insert_id;
		}
		return $stmt->affected_rows;
	}
	
	/**
	 * Prepare a command (other then select, e.g. DELETE OR UPDATE)
	 * 
	 * @throws Exception if Statement could not be prepared.
	 * @param string SQL statement. Must be a select or similar.
	 * @param array Bind values. One for each "?"
	 * @param string Character list of types. E.g. "ssds"
	 * @return object MySQLi Statement object, null on failure
	 */
	static function prepareCommand($sql,$values=array(),$types=null) {
		if(self::$resource) {
			$stmt = self::$resource->prepare($sql);
			if($stmt) {
				/**
				 * Use sequence of "s" (String) if no type list given.
				 */
				if($types === null) {
					$types = str_repeat('s',count($values)); 
				}
				/**
				 * Bind parameters
				 */
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
