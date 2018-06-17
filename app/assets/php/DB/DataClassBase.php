<?php

abstract class DBItem
{
	protected $db;
	public $id;
	protected $table;
	
	protected function __construct(Db $db, int $id = null, string $tableName = null)
	{
		$this->db = $db;
		$this->table = $tableName;
		
		if ($id)
		{
			$this->id = $id;
		}
		else if ($tableName)
		{
			$this->insertTable($tableName);
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
	
	protected function readTable(string $tableName)
	{
		$query = 'select * from ' . $tableName . ' where id = ?';
		$row = $this->db->select($query, 'i', array(&$this->id))[0];
		$this->setPropertiesFromRow($row);
	}
	
	protected function readBase()
	{
		$this->readTable($this->table);
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
	
	protected function deleteTable(string $tableName)
	{
		$query = 'delete from ' . $tableName . ' where id = ?';
		$this->db->query($query, 'i', array(&$this->id));
	}
	
	protected function deleteBase()
	{
		$this->deleteTable($this->table);
	}
	
	protected function insertTable(string $tableName)
	{
		$query = 'insert into ' . $tableName . ' values ()';
		$this->db->query($query);
		
		$this->id = $this->db->insert_id();
	}
}

abstract class DBItemParent extends DBItem
{
	public $typeID;
	private $parentTable;
	
	// Extra properties
	public $type;
	
	protected function __construct(Db $db, int $id = null, int $typeID = null, string $tableName, string $subordinateTableName = null)
	{
		parent::__construct($db, $id, $subordinateTableName);
		
		$this->parentTable = $tableName;
		
		if (!$id && $tableName)
		{
			$this->insertParent($typeID);
			if ($subordinateTableName)
			{
				$this->insertSub();
			}
		}
	}
	
	private function insertParent(int $typeID = null)
	{
		$this->insertTable($this->parentTable);
		
		$this->typeID = $typeID;
		$this->write();
	}
	
	private function insertSub()
	{
		$query = 'insert into ' . $this->table . ' (baseID) values (?)';
		$this->db->query($query, 'i', array(&$this->id));
	}
	
	public abstract function readExtra();
	
	protected function readType(string $typeName)
	{
		$this->type = new $typeName($this->db, $this->typeID);
		$this->type->read();
	}
	
	protected function writeTypeID()
	{
		$this->writeBase($this->typeID, 'typeID', $this->parentTable);
	}
	
	protected function writeSub($value, string $columnName, bool $quote = false, bool $allowNull = false, bool $boolean = false)
	{
		$this->writeBaseImpl($value, $columnName, 'baseID', $this->table, $quote, $allowNull, $boolean);
	}
	
	public function readWhole($subordinateTableName = null)
	{
		$this->readTable($this->parentTable);
		
		if ($subordinateTableName)
		{
			$this->readSub($subordinateTableName);
		}
	}
	
	private function readSub(string $tableName)
	{
		$query = 'select * from ' . $tableName . ' where baseID = ?';
		$row = $this->db->select($query, 'i', array(&$this->id))[0];
		$this->setPropertiesFromRow($row);
	}
	
	protected function deleteBase()
	{
		parent::deleteTable($this->parentTable);
	}
}

abstract class DBItemPositioned extends DBItem
{
	public $position;
	
	protected function __construct(Db $db, string $table, int $id = null)
	{
		parent::__construct($db, $id, $table);
		
		if (!$id)
		{
			$this->writePosition();
		}
	}
	
	protected function writePositionValue()
	{
		$this->writeBase($this->position, 'position', $this->table);
	}
	
	protected function writePositionBase()
	{
		$query = 'select case when max(position) is not null then max(position) + 1 else 1 end as next_position from ' . $this->table;
		$row = $this->db->select($query)[0];
		
		$this->position = $row['next_position'];
		$this->writePositionValue();
	}
	
	public abstract function writePosition();
}

?>