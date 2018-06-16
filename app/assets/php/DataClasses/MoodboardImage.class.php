<?php

class MoodboardImage extends DBItem
{
	public $name;
	public $description;
	
	// Extra properties
	public $sections;
	
	private static $tableName = 'mb_image';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->name, 'name', self::$tableName, true);
		$this->writeBase($this->description, 'description', self::$tableName, true, true);
	}
	
	public function read()
	{
		parent::readBase(self::$tableName);
	}
	
	public function readExtra()
	{
		$query = 'select s.name from mb_image i join mb_section_image si on si.imageID = i.id join mb_section s on s.id = si.sectionID where i.id = ?';
		$rows = $this->db->select($query, 'i', array(&$this->id));
		
		$this->sections = $rows;
	}
	
	public function delete()
	{
		parent::deleteBase(self::$tableName);
	}
}

?>