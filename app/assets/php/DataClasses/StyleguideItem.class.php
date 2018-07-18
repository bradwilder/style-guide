<?php

class StyleguideItem extends DBItemParent
{
	public $name;
	public $colLg;
	public $colMd;
	public $colSm;
	public $colXs;
	public $subsectionID;
	public $position;
	
	private static $tableName = 'sg_item';
	private static $typeTableName = 'sg_item_type';
	private static $typeClassName = 'StyleguideItemType';
	
	public function __construct(Db $db, int $id = null, string $code = null, string $subordinateTableName = null, int $subsectionID = null)
	{
		if (!$id && ($code xor $subordinateTableName))
		{
			return;
		}
		
		parent::__construct($db, self::$tableName, $id, $code, self::$typeTableName, self::$typeClassName, $subordinateTableName);
		
		$this->addColumn('name', new DBColumn(DBColumnType::String));
		$this->addColumn('colLg', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('colMd', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('colSm', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('colXs', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('subsectionID', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('position', new DBColumn(DBColumnType::Numeric));
		
		$this->subsectionID = $subsectionID;
		
		if (!$id && $subsectionID)
		{
			$this->writePosition();
		}
	}
	
	public function readSubExtra() {}
	
	public function readItemData()
	{
		throw new Exception('Not implemented on the base type');
	}
	
	private function writePosition()
	{
		if ($this->subsectionID)
		{
			$query = 'select case when max(position) is not null then max(position) + 1 else 1 end as next_position from ' . self::$tableName . ' where subsectionID = ?';
			$row = $this->db->select($query, 'i', [&$this->subsectionID])[0];
			
			$this->position = $row['next_position'];
			$this->write();
		}
	}
	
	public static function nameExists(Db $db, string $name, int $selfID = null)
	{
		$query = 'select count(*) as count from ' . self::$tableName . ' where name = ?';
		$types = 's';
		$params = [&$name];
		if ($selfID)
		{
			$query .= ' and id <> ?';
			$types .= 'i';
			$params []= &$selfID;
		}
		
		$rows = $db->select($query, $types, $params);
		return ($rows[0]['count'] != 0);
	}
}

?>