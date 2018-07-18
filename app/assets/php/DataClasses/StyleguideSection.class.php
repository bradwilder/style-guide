<?php

class StyleguideSection extends DBItem
{
	public $name;
	public $enabled;
	public $userCreated;
	public $position;
	
	// Extra properties
	public $subsections = [];
	
	private static $tableName = 'sg_section';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('name', new DBColumn(DBColumnType::String));
		$this->addColumn('enabled', new DBColumn(DBColumnType::Boolean));
		$this->addColumn('userCreated', new DBColumn(DBColumnType::Boolean));
		$this->addColumn('position', new DBColumn(DBColumnType::Numeric));
		
		if (!$id)
		{
			$positioner = new DBItemPositioner($db, self::$tableName);
			$this->position = $positioner->getNextPosition();
			$this->write();
		}
	}
	
	public function readExtra(bool $enabled = null, bool $readFullItems = false)
	{
		$query = 'select id from sg_subsection where sectionID = ? and parentSubsectionID is null ' . (isset($enabled) ? 'and enabled = ' . ($enabled ? '1' : '0') : '') . ' order by position';
		$rows = $this->db->select($query, 'i', [&$this->id]);
		foreach ($rows as $row)
		{
			$styleguideSubsection = new StyleguideSubsection($this->db, $row['id']);
			$styleguideSubsection->read();
			$styleguideSubsection->readExtra($enabled, $readFullItems);
			
			$this->subsections []= $styleguideSubsection;
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