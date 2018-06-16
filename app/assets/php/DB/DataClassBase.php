<?php

abstract class DBItem
{
	protected $db;
	public $id;
	
	protected function __construct(Db $db, int $id = null, string $tableName = null)
	{
		$this->db = $db;
		
		if ($id)
		{
			$this->id = $id;
		}
		else if ($tableName)
		{
			$this->insert($tableName);
		}
	}
	
	public abstract function write();
	public abstract function read();
	public abstract function delete();
	
	protected function writeBase($value, string $columnName, string $tableName, bool $quote = false, bool $allowNull = false, bool $boolean = false)
	{
		$this->writeBaseImpl($value, $columnName, 'id', $tableName, $quote, $allowNull, $boolean);
	}
	
	protected function writeBaseImpl($value, string $columnName, string $idName, string $tableName, bool $quote = false, bool $allowNull = false, bool $boolean = false)
	{
		if (($allowNull && isset($value)) || (!$allowNull && $value) || $boolean)
		{
			if ($allowNull && ($value === 0 || $value === ''))
			{
				$value =  null;
			}
			
			$query = 'update ' . $tableName . ' set ' . $columnName . ' = ? where ' . $idName . ' = ?';
			$types;
			if ($quote)
			{
				$types = 'si';
			}
			else
			{
				$types = 'ii';
			}
			$this->db->query($query, $types, array(&$value, &$this->id));
		}
	}
	
	protected function readBase(string $tableName)
	{
		$query = 'select * from ' . $tableName . ' where id = ?';
		$row = $this->db->select($query, 'i', array(&$this->id))[0];
		$this->setPropertiesFromRow($row);
	}
	
	protected function setPropertiesFromRow(array $row)
	{
		foreach ($row as $key => $value)
		{
			if ($key != 'id')
			{
				$this->{$key} = $value;
			}
		}
	}
	
	protected function deleteBase(string $tableName)
	{
		$query = 'delete from ' . $tableName . ' where id = ?';
		$this->db->query($query, 'i', array(&$this->id));
	}
	
	protected function insert(string $tableName)
	{
		$query = 'insert into ' . $tableName . ' values ()';
		$this->db->query($query);
		
		$this->id = $this->db->insert_id();
	}
}

abstract class DBItemParent extends DBItem
{
	public $typeID;
	
	// Extra properties
	public $type;
	
	protected function __construct(Db $db, int $id = null, int $typeID = null, string $tableName = null, string $subordinateTableName = null)
	{
		parent::__construct($db, $id);
		
		if (!$id && $tableName)
		{
			$this->insertParent($typeID, $tableName);
			if ($subordinateTableName)
			{
				$this->insertSub($subordinateTableName);
			}
		}
	}
	
	private function insertParent(int $typeID = null, string $tableName)
	{
		$this->insert($tableName);
		
		$this->typeID = $typeID;
		$this->write();
	}
	
	private function insertSub(string $tableName)
	{
		$query = 'insert into ' . $tableName . ' (baseID) values (?)';
		$this->db->query($query, 'i', array(&$this->id));
	}
	
	public abstract function readExtra();
	
	protected function readType(string $typeName)
	{
		$this->type = new $typeName($this->db, $this->typeID);
		$this->type->read();
	}
	
	protected function writeTypeID(string $tableName)
	{
		$this->writeBase($this->typeID, 'typeID', $tableName);
	}
	
	protected function writeSub($value, string $columnName, string $tableName, bool $quote = false, bool $allowNull = false, bool $boolean = false)
	{
		$this->writeBaseImpl($value, $columnName, 'baseID', $tableName, $quote, $allowNull, $boolean);
	}
	
	public function readWhole(string $tableName, string $subordinateTableName = null)
	{
		$this->readBase($tableName);
		
		if ($subordinateTableName)
		{
			$this->readSub($subordinateTableName);
		}
	}
	
	protected function readSub(string $tableName)
	{
		$query = 'select * from ' . $tableName . ' where baseID = ?';
		$row = $this->db->select($query, 'i', array(&$this->id))[0];
		$this->setPropertiesFromRow($row);
	}
}

abstract class DBItemPositioned extends DBItem
{
	public $position;
	
	protected function __construct(Db $db, int $id = null, string $tableName = null)
	{
		parent::__construct($db, $id, $tableName);
		
		if (!$id && $tableName)
		{
			$this->writePosition($tableName);
		}
	}
	
	protected function writePositionValue(string $tableName)
	{
		$this->writeBase($this->position, 'position', $tableName);
	}
	
	public abstract function writePosition();
	
	protected function writePositionBase(string $tableName)
	{
		$query = 'select case when max(position) is not null then max(position) + 1 else 1 end as next_position from ' . $tableName;
		$row = $this->db->select($query)[0];
		
		$this->position = $row['next_position'];
		$this->write();
	}
}

?>