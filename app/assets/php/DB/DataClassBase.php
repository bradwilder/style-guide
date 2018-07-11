<?php

abstract class DBItem
{
	protected $db;
	public $id;
	protected $table;
	
	protected function __construct(Db $db, string $table, int $id = null)
	{
		$this->db = $db;
		$this->table = $table;
		$this->id = $id;
		
		if (!$id)
		{
			$this->insertTable();
		}
	}
	
	public abstract function write();
	public abstract function read();
	public abstract function delete();
	
	protected function writeBase($value, string $columnName, bool $quote = false, bool $allowNull = false, bool $boolean = false)
	{
		$this->writeBaseTable($value, $columnName, 'id', $this->table, $quote, $allowNull, $boolean);
	}
	
	protected function writeBaseTable($value, string $columnName, string $idName, string $table, bool $quote = false, bool $allowNull = false, bool $boolean = false)
	{
		if (($allowNull && isset($value)) || (!$allowNull && $value) || $boolean)
		{
			if ($allowNull && ($value === 0 || $value === ''))
			{
				$value =  null;
			}
			
			$query = 'update ' . $table . ' set ' . $columnName . ' = ? where ' . $idName . ' = ?';
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
	
	protected function readTable(string $table, string $primaryColName)
	{
		$query = 'select * from ' . $table . ' where ' . $primaryColName . ' = ?';
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
	
	protected function deleteBase()
	{
		$query = 'delete from ' . $this->table . ' where id = ?';
		$this->db->query($query, 'i', array(&$this->id));
	}
	
	protected function insertTable()
	{
		$query = 'insert into ' . $this->table . ' values ()';
		$this->db->query($query);
		
		$this->id = $this->db->insert_id();
	}
}

abstract class DBItemParent extends DBItem
{
	public $typeID;
	private $subTable;
	private $typeTable;
	private $typeClass;
	
	// Extra properties
	public $type;
	
	protected function __construct(Db $db, string $table, int $id = null, string $code = null, string $typeTable, string $typeClass, string $subTable = null)
	{
		parent::__construct($db, $table, $id);
		
		$this->subTable = $subTable;
		$this->typeTable = $typeTable;
		$this->typeClass = $typeClass;
		
		if (!$id)
		{
			$this->typeID = $this->typeIDFromCode($code);
			
			$this->writeTypeID();
			if ($this->subTable)
			{
				$query = 'insert into ' . $this->subTable . ' (baseID) values (?)';
				$this->db->query($query, 'i', array(&$this->id));
			}
		}
	}
	
	private function typeIDFromCode(string $code = null)
	{
		if ($code)
		{
			$query = 'select id from ' . $this->typeTable . ' where code = ?';
			$rows = $this->db->select($query, 's', array(&$code));
			if (count($rows) > 0)
			{
				return $rows[0]['id'];
			}
		}
	}
	
	public abstract function readSubExtra();
	
	public abstract function writeSubTable();
	
	public function read(string $subordinateTableName = null)
	{
		$this->readTable($this->table, 'id');
		
		if ($subordinateTableName)
		{
			$this->readTable($subordinateTableName, 'baseID');
		}
	}
	
	public function readExtra()
	{
		$this->type = new $this->typeClass($this->db, $this->typeID);
		$this->type->read();
		
		$this->readSubExtra();
	}
	
	public function write()
	{
		$this->writeTypeID();
		$this->writeSubTable();
	}
	
	private function writeTypeID()
	{
		$this->writeBase($this->typeID, 'typeID');
	}
	
	protected function writeSub($value, string $columnName, bool $quote = false, bool $allowNull = false, bool $boolean = false)
	{
		$this->writeBaseTable($value, $columnName, 'baseID', $this->subTable, $quote, $allowNull, $boolean);
	}
	
	public function delete()
	{
		parent::deleteBase();
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