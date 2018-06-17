<?php

abstract class DBItem
{
	protected $db;
	public $id;
	protected $table;
	
	protected function __construct(Db $db, string $tableName, int $id = null, bool $create = true)
	{
		$this->db = $db;
		$this->table = $tableName;
		$this->id = $id;
		
		if (!$id && $create)
		{
			$this->id = $this->insertTable($tableName);
		}
	}
	
	public abstract function write();
	public abstract function read();
	public abstract function delete();
	
	protected function writeBase($value, string $columnName, bool $quote = false, bool $allowNull = false, bool $boolean = false)
	{
		$this->writeBaseTable($value, $columnName, 'id', $this->table, $quote, $allowNull, $boolean);
	}
	
	protected function writeBaseTable($value, string $columnName, string $idName, string $tableName, bool $quote = false, bool $allowNull = false, bool $boolean = false)
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
	
	protected function readTable(string $tableName, string $primaryColName)
	{
		$query = 'select * from ' . $tableName . ' where ' . $primaryColName . ' = ?';
		$row = $this->db->select($query, 'i', array(&$this->id))[0];
		$this->setPropertiesFromRow($row);
	}
	
	protected function readBase()
	{
		$this->readTable($this->table, 'id');
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
		
		return $this->db->insert_id();
	}
}

abstract class DBItemParent extends DBItem
{
	public $typeID;
	private $subTable;
	
	// Extra properties
	public $type;
	
	protected function __construct(Db $db, int $id = null, int $typeID = null, string $tableName, string $subordinateTableName = null)
	{
		parent::__construct($db, $tableName, $id, false);
		
		$this->subTable = $subordinateTableName;
		
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
		$this->id = $this->insertTable($this->table);
		
		$this->writeTypeID($typeID);
	}
	
	private function insertSub()
	{
		$query = 'insert into ' . $this->subTable . ' (baseID) values (?)';
		$this->db->query($query, 'i', array(&$this->id));
	}
	
	public abstract function readExtra();
	
	protected function readType(string $typeName)
	{
		$this->type = new $typeName($this->db, $this->typeID);
		$this->type->read();
	}
	
	protected function writeTypeID(int $typeID = null)
	{
		if ($typeID)
		{
			$this->typeID = $typeID;
		}
		
		$this->writeBase($this->typeID, 'typeID');
	}
	
	protected function writeSub($value, string $columnName, bool $quote = false, bool $allowNull = false, bool $boolean = false)
	{
		$this->writeBaseTable($value, $columnName, 'baseID', $this->subTable, $quote, $allowNull, $boolean);
	}
	
	public function readWhole($subordinateTableName = null)
	{
		$this->readTable($this->table, 'id');
		
		if ($subordinateTableName)
		{
			$this->readTable($subordinateTableName, 'baseID');
		}
	}
}

abstract class DBItemPositioned extends DBItem
{
	public $position;
	
	protected function __construct(Db $db, string $table, int $id = null)
	{
		parent::__construct($db, $table, $id);
		
		if (!$id)
		{
			$this->writePosition();
		}
	}
	
	protected function writePositionValue()
	{
		$this->writeBase($this->position, 'position');
	}
	
	public function writePosition()
	{
		$query = 'select case when max(position) is not null then max(position) + 1 else 1 end as next_position from ' . $this->table;
		$row = $this->db->select($query)[0];
		
		$this->position = $row['next_position'];
		$this->writePositionValue();
	}
}

?>