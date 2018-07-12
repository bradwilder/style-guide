<?php

class StyleguideSubsection extends DBItem
{
	public $name;
	public $description;
	public $position;
	public $enabled;
	public $sectionID;
	public $parentSubsectionID;
	
	// Extra properties
	public $subSubsections = array();
	public $items = array();
	
	private static $tableName = 'sg_subsection';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('name', new DBColumn(DBColumnType::String));
		$this->addColumn('description', new DBColumn(DBColumnType::String));
		$this->addColumn('position', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('enabled', new DBColumn(DBColumnType::Boolean));
		$this->addColumn('sectionID', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('parentSubsectionID', new DBColumn(DBColumnType::Numeric, true));
	}
	
	public function readExtra(bool $enabled = null)
	{
		$query = 'select id from sg_subsection where sectionID = ? and parentSubsectionID = ? ' . (isset($enabled) ? 'and enabled = ' . ($enabled ? '1' : '0') : '') . ' order by position';
		$rows = $this->db->select($query, 'ii', array(&$this->sectionID, &$this->id));
		foreach ($rows as $row)
		{
			$styleguideSubSubsection = new StyleguideSubsection($this->db, $row['id']);
			$styleguideSubSubsection->read();
			$styleguideSubSubsection->readExtra($enabled);
			
			$this->subSubsections []= $styleguideSubSubsection;
		}
		
		$query = 'select id from sg_item where subsectionID = ? order by position';
		$rows = $this->db->select($query, 'i', array(&$this->id));
		foreach ($rows as $row)
		{
			$styleguideItem = new StyleguideItem($this->db, $row['id']);
			$styleguideItem->read();
			
			$this->items []= $styleguideItem;
		}
	}
	
	public function writePosition()
	{
		if ($this->sectionID)
		{
			$query = 'select case when max(position) is not null then max(position) + 1 else 1 end as next_position from ' . self::$tableName . ' where sectionID = ? and parentSubsectionID <=> ?';
			$row = $this->db->select($query, 'ii', array(&$this->sectionID, &$this->parentSubsectionID))[0];
			
			$this->position = $row['next_position'];
			$this->write();
		}
	}
	
	private static function nameExists(string $name, int $sectionID, int $parentSubsectionID = null, int $selfID = null)
	{
		$db = new Db();
		
		$query = 'select count(*) as count from ' . self::$tableName . ' where name = ? and sectionID = ? and parentSubsectionID <=> ?';
		$types = 'si';
		$params = array(&$name, &$sectionID, &$parentSubsectionID);
		if ($selfID)
		{
			$query .= ' and id <> ?';
			$types .= 'i';
			$params []= &$selfID;
		}
		
		$rows = $db->select($query, $types, $params);
		return ($rows[0]['count'] != 0);
	}
	
	public static function nameExistsNew(string $name, int $sectionID, int $parentSubsectionID = null)
	{
		return self::nameExists($name, $sectionID, $parentSubsectionID);
	}
	
	public static function nameExistsEdit(string $name, int $selfID)
	{
		$subsection = new StyleguideSubsection(new Db(), $selfID);
		$subsection->read();
		
		return self::nameExists($name, $subsection->sectionID, $subsection->parentSubsectionID, $subsection->id);
	}
}

?>