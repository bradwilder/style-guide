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
	
	public function __construct(Db $db, int $id = null, string $code = null, string $subordinateTableName = null)
	{
		if (!$id && ($code xor $subordinateTableName))
		{
			return;
		}
		
		parent::__construct($db, self::$tableName, $id, $code, self::$typeTableName, self::$typeClassName, $subordinateTableName);
	}
	
	public function writeSubTable()
	{
		$this->writeBase($this->name, 'name', true);
		$this->writeBase($this->colLg, 'colLg');
		$this->writeBase($this->colMd, 'colMd');
		$this->writeBase($this->colSm, 'colSm');
		$this->writeBase($this->colXs, 'colXs');
		$this->writeBase($this->subsectionID, 'subsectionID');
		$this->writeBase($this->position, 'position');
	}
	
	public function readSubExtra() {}
	
	public function readItemData()
	{
		throw new Exception('Not implemented on the base type');
	}
	
	public function writePosition()
	{
		if ($this->subsectionID)
		{
			$query = 'select case when max(position) is not null then max(position) + 1 else 1 end as next_position from ' . self::$tableName . ' where subsectionID = ?';
			$row = $this->db->select($query, 'i', array(&$this->subsectionID))[0];
			
			$this->position = $row['next_position'];
			$this->write();
		}
	}
	
	public static function nameExists(string $name, int $selfID = null)
	{
		$db = new Db();
		
		$query = 'select count(*) as count from ' . self::$tableName . ' where name = ?';
		$types = 's';
		$params = array(&$name);
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