<?php

class Db
{
	private static $connection;
	
	public function __construct(string $dbName = null)
	{
		$config = parse_ini_file(__SITE_PATH . '/../config.ini');
		if ($config['username'] == '')
		{
			return false;
		}
		
		self::$connection = new mysqli($config['host'], $config['username'], $config['password'], $dbName ? $dbName : $config['dbname']);
	}
	
	private function createStatement(string $query, string $types = null, array $params = null)
	{
		$stmt = self::$connection->prepare($query);
		if (!$stmt)
		{
			throw new Exception("Can't prepare statement: " . $this->error());
		}
		
		if ($types && $params)
		{
			array_unshift($params, $types);
			call_user_func_array(array($stmt, 'bind_param'), $params);
		}
		return $stmt;
	}
	
	public function query(string $query, string $types = null, array $params = null)
	{
		$stmt = $this->createStatement($query, $types, $params);
		return $stmt->execute();
	}
	
	public function select(string $query, string $types = null, array $params = null)
	{
		$stmt = $this->createStatement($query, $types, $params);
		$exec = $stmt->execute();
		if ($exec === false)
		{
			return false;
		}
		
		$result = $stmt->get_result();
		$rows = array();
		while ($row = $result->fetch_assoc())
		{
			$rows[] = $row;
		}
		return $rows;
	}
	
	public function error()
	{
		return self::$connection->error;
	}
	
	public function insert_id()
	{
		return self::$connection->insert_id;
	}
	
	public function close()
	{
		return self::$connection->close();
	}
}

?>