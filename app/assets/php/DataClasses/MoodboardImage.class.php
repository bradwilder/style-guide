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
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('name', new DBColumn(DBColumnType::String));
		$this->addColumn('description', new DBColumn(DBColumnType::String, true));
	}
	
	public function readExtra()
	{
		$query = 'select s.name from ' . self::$tableName . ' i join mb_section_image si on si.imageID = i.id join mb_section s on s.id = si.sectionID where i.id = ?';
		$rows = $this->db->select($query, 'i', [&$this->id]);
		
		$this->sections = $rows;
	}
}

?>