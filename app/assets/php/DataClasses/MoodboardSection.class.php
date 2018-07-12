<?php

class MoodboardSection extends DBItemPositioned
{
	public $name;
	public $description;
	public $modeID;
	
	// Extra properties
	public $images;
	public $modeName;
	
	private static $tableName = 'mb_section';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('name', new DBColumn(DBColumnType::String));
		$this->addColumn('description', new DBColumn(DBColumnType::String, true));
		$this->addColumn('modeID', new DBColumn(DBColumnType::Numeric));
	}
	
	public function readExtra()
	{
		$query = 'select i.*, si.id as section_image_id from mb_section s join mb_section_image si on si.sectionID = s.id join mb_image i on i.id = si.imageID where s.id = ? order by si.position';
		$rows = $this->db->select($query, 'i', array(&$this->id));
		
		$this->images = $rows;
		
		$query = 'select name from mb_mode where id = ?';
		$rows = $this->db->select($query, 'i', array(&$this->modeID));
		
		$this->modeName = $rows[0]['name'];
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