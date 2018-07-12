<?php

class Color extends DBItem
{
	public $name;
	public $hex;
	public $variant1;
	public $variant2;
	
	private static $tableName = 'sg_color';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('name', new DBColumn(DBColumnType::String));
		$this->addColumn('hex', new DBColumn(DBColumnType::String));
		$this->addColumn('variant1', new DBColumn(DBColumnType::String, true));
		$this->addColumn('variant2', new DBColumn(DBColumnType::String, true));
	}
	
	public function write()
	{
		if ((isset($this->variant2) && strlen($this->variant2) > 0) && (!isset($this->variant1)) || strlen($this->variant1) == 0)
		{
			$this->variant1 = $this->variant2;
			$this->variant2 = '';
		}
		
		parent::write();
	}
	
	public function delete()
	{
		$query = 'select baseID from sg_color_item where color1ID = ? || color2ID = ? || color3ID = ? || color4ID = ? || color5ID = ? || color6ID = ?';
		$rows = $this->db->select($query, 'iiiiii', array(&$this->id, &$this->id, &$this->id, &$this->id, &$this->id, &$this->id));
		
		foreach ($rows as $row)
		{
			$item = new StyleguideColorItem($this->db, $row['baseID']);
			$item->delete();
		}
		
		parent::delete();
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
		$count = $rows[0]['count'];
		return ($count != 0);
	}
}

?>