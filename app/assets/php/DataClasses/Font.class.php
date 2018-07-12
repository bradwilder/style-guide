<?php

class Font extends DBItemParent
{
	public $name;
	public $alphabetID;
	
	// Extra properties
	public $alphabet;
	
	private static $tableName = 'sg_font';
	private static $typeTableName = 'sg_font_type';
	private static $typeClassName = 'FontType';
	
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
		$this->writeBase($this->name, 'name', DBColumnType::String);
		$this->writeBase($this->alphabetID, 'alphabetID', DBColumnType::Numeric, true);
	}
	
	public function readSubExtra()
	{
		if ($this->alphabetID)
		{
			$this->alphabet = new FontAlphabet($this->db, $this->alphabetID);
			$this->alphabet->read();
		}
	}
	
	public function delete()
	{
		$query = 'select baseID from sg_font_family where fontID = ?';
		$rows = $this->db->select($query, 'i', array(&$this->id));
		
		foreach ($rows as $row)
		{
			$item = new StyleguideFontFamilyItem($this->db, $row['baseID']);
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