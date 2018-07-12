<?php

abstract class DBColumnType
{
	const Numeric = 0;
	const String = 1;
	const Boolean = 2;
	const Date = 3;
}

class DBColumn
{
	public $type;
	public $allowNull;
	
	public function __construct(int $type = DBColumnType::Numeric, bool $allowNull = false)
	{
		$this->type = $type;
		$this->allowNull = $allowNull;
	}
}

abstract class DBItem_base
{
	public $id;
	
	protected $db;
	protected $table;
	
	protected $columns = array();
	
	protected function __construct(Db $db, string $table, int $id = null)
	{
		$this->db = $db;
		$this->table = $table;
		
		if ($id)
		{
			$this->id = $id;
		}
		else
		{
			$query = 'insert into ' . $this->table . ' values ()';
			$this->db->query($query);
			
			$this->id = $this->db->insert_id();
		}
	}
	
	public function addColumn(string $name, DBColumn $column)
	{
		$this->columns[$name] = $column;
	}
	
	public abstract function write();
	public abstract function read();
	public abstract function delete();
	
	protected function writeBase($value, string $columnName, DBColumn $column)
	{
		$this->writeBaseTable($value, $columnName, 'id', $this->table, $column);
	}
	
	protected function writeBaseTable($value, string $columnName, string $idName, string $table, $column)
	{
		if (($column->allowNull && isset($value)) || (!$column->allowNull && $value) || $column->type === DBColumnType::Boolean)
		{
			if ($column->allowNull && ($value === 0 || $value === ''))
			{
				$value = null;
			}
			
			if ($column->type === DBColumnType::Date)
			{
				$value = date('Y-m-d H:i:s', $value);
			}
			
			$query = 'update ' . $table . ' set ' . $columnName . ' = ? where ' . $idName . ' = ?';
			$types = ($column->type === DBColumnType::String || $column->type === DBColumnType::Date ? 's' : 'i') . 'i';
			$this->db->query($query, $types, array(&$value, &$this->id));
		}
	}
	
	protected function readTable(string $table, string $primaryColName, $columns)
	{
		$query = 'select * from ' . $table . ' where ' . $primaryColName . ' = ?';
		$row = $this->db->select($query, 'i', array(&$this->id))[0];
		foreach ($row as $key => $value)
		{
			if ($key != 'id' && $key != 'baseID')
			{
				$column = $columns[$key];
				
				if ($column->type === DBColumnType::Boolean)
				{
					$this->{$key} = ($value == 1);
				}
				else if ($column->type === DBColumnType::Date)
				{
					$this->{$key} = strtotime($value);
				}
				else
				{
					$this->{$key} = $value;
				}
			}
		}
	}
}

class DBItem extends DBItem_base
{
	public function write()
	{
		foreach ($this->columns as $name => $column)
		{
			$this->writeBase($this->$name, $name, $column);
		}
	}
	
	public function read()
	{
		$this->readTable($this->table, 'id', $this->columns);
	}
	
	public function delete()
	{
		$query = 'delete from ' . $this->table . ' where id = ?';
		$this->db->query($query, 'i', array(&$this->id));
	}
}

abstract class DBItemParent extends DBItem
{
	public $typeID;
	
	private $subTable;
	private $typeTable;
	private $typeClass;
	private $subColumns = array();
	
	// Extra properties
	public $type;
	
	protected function __construct(Db $db, string $table, int $id = null, string $code = null, string $typeTable, string $typeClass, string $subTable = null)
	{
		parent::__construct($db, $table, $id);
		
		$this->subTable = $subTable;
		$this->typeTable = $typeTable;
		$this->typeClass = $typeClass;
		
		$this->addColumn('typeID', new DBColumn(DBColumnType::Numeric));
		
		if (!$id)
		{
			$this->typeID = $this->typeIDFromCode($code);
			parent::write();
			
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
	
	public function addSubColumn(string $name, DBColumn $column)
	{
		$this->subColumns[$name] = $column;
	}
	
	public abstract function readSubExtra();
	
	public function read(bool $readSub = false)
	{
		$this->readTable($this->table, 'id', $this->columns);
		
		if ($readSub)
		{
			$this->readTable($this->subTable, 'baseID', $this->subColumns);
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
		parent::write();
		
		foreach ($this->subColumns as $name => $column)
		{
			$this->writeBaseTable($this->$name, $name, 'baseID', $this->subTable, $column);
		}
	}
}

class DBItemPositioner
{
	private $db;
	private $table;
	
	public function __construct(Db $db, string $table)
	{
		$this->db = $db;
		$this->table = $table;
	}
	
	public function getNextPosition()
	{
		$query = 'select case when max(position) is not null then max(position) + 1 else 1 end as next_position from ' . $this->table;
		$row = $this->db->select($query)[0];
		
		return $row['next_position'];
	}
}

?>