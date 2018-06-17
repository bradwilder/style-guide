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
	
	public function __construct(Db $db, int $id = null, $code = null, $subordinateTableName = null)
	{
		if (!$id && ($code xor $subordinateTableName))
		{
			return;
		}
		
		parent::__construct($db, $id, $this->typeIDFromCode($db, $code), self::$tableName, $subordinateTableName);
	}
	
	private function typeIDFromCode(Db $db, string $code = null)
	{
		if ($code)
		{
			$query = 'select id from sg_item_type where code = ?';
			$rows = $db->select($query, 's', array(&$code));
			if (count($rows) > 0)
			{
				return $rows[0]['id'];
			}
		}
	}
	
	public function write()
	{
		$this->writeTypeID();
		
		$this->writeBase($this->name, 'name', true);
		$this->writeBase($this->colLg, 'colLg');
		$this->writeBase($this->colMd, 'colMd');
		$this->writeBase($this->colSm, 'colSm');
		$this->writeBase($this->colXs, 'colXs');
		$this->writeBase($this->subsectionID, 'subsectionID');
		$this->writeBase($this->position, 'position');
	}
	
	public function read(string $subordinateTableName = null)
	{
		$this->readWhole($subordinateTableName);
	}
	
	public function readExtra()
	{
		$this->readType('StyleguideItemType');
	}
	
	public function readItemData()
	{
		throw new Exception('Not implemented on the base type');
	}

	public function delete()
	{
		parent::deleteBase();
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