<?php

class Font extends DBItemParent
{
	public $name;
	public $alphabetID;
	
	// Extra properties
	public $alphabet;
	
	private static $tableName = 'sg_font';
	
	public function __construct(Db $db, int $id = null, string $code = null, string $subordinateTableName = null)
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
			$query = 'select id from sg_font_type where code = ?';
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
		$this->writeBase($this->alphabetID, 'alphabetID', false, true);
	}
	
	public function read($subordinateTableName = null)
	{
		$this->readWhole($subordinateTableName);
	}
	
	public function readExtra()
	{
		$this->readType('FontType');
		
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
			$colorItem = new StyleguideFontFamilyItem($this->db, $row['baseID']);
			$colorItem->delete();
		}
		
		parent::deleteBase();
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