<?php

class StyleguideSection extends DBItemPositioned
{
	public $name;
	public $enabled;
	public $userCreated;
	
	// Extra properties
	public $subsections = array();
	
	private static $tableName = 'sg_section';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
	}
	
	public function writeSubTable()
	{
		$this->writeBase($this->name, 'name', true);
		$this->writeBase($this->enabled, 'enabled', false, false, true);
		$this->writeBase($this->userCreated, 'userCreated', false, false, true);
	}
	
	public function read()
	{
		$query = 'select * from ' . self::$tableName . ' where id = ?';
		$row = $this->db->select($query, 'i', array(&$this->id))[0];
		
		$this->name = $row['name'];
		$this->position = $row['position'];
		$this->enabled = ($row['enabled'] == 1);
		$this->userCreated = ($row['userCreated'] == 1);
	}
	
	public function readExtra(bool $enabled = null)
	{
		$query = 'select id from sg_subsection where sectionID = ? and parentSubsectionID is null ' . (isset($enabled) ? 'and enabled = ' . ($enabled ? '1' : '0') : '') . ' order by position';
		$rows = $this->db->select($query, 'i', array(&$this->id));
		foreach ($rows as $row)
		{
			$styleguideSubsection = new StyleguideSubsection($this->db, $row['id']);
			$styleguideSubsection->read();
			$styleguideSubsection->readExtra($enabled);
			
			$this->subsections []= $styleguideSubsection;
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