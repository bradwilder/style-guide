<?php

class Db
{
	protected static $connection;
	
	public function connect()
	{
		if (!isset(self::$connection))
		{
			$this->setConnection();
		}
		
		return self::$connection;
	}
	
	private function setConnection(string $dbName = null)
	{
		$config = parse_ini_file(__SITE_PATH . '/../config.ini');
		if ($config['username'] == '')
		{
			return false;
		}
		
		self::$connection = new mysqli($config['host'], $config['username'], $config['password'], $dbName ? $dbName : $config['dbname']);
	}
	
	public function query(string $query, string $types = null, array $params = null)
	{
		$stmt = $this->createStatement($query, $types, $params);
		return $stmt->execute();
	}
	
	private function createStatement(string $query, string $types = null, array $params = null)
	{
		$stmt = $this->connect()->prepare($query);
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
		return $this->connect()->error;
	}
	
	public function insert_id()
	{
		return $this->connect()->insert_id;
	}
	
	public function close()
	{
		return $this->connect()->close();
	}
	
	public function changeDatabase(string $dbName)
	{
		$this->setConnection($dbName);
	}
}

?>