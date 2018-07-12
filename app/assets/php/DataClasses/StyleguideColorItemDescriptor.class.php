<?php

class StyleguideColorItemDescriptor extends DBItem
{
	public $description;
	public $itemID;
	public $position;
	
	private static $tableName = 'sg_color_descriptor';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('description', new DBColumn(DBColumnType::String));
		$this->addColumn('itemID', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('position', new DBColumn(DBColumnType::Numeric));
	}
	
	public function writePosition()
	{
		if ($this->itemID)
		{
			$query = 'select case when max(position) is not null then max(position) + 1 else 1 end as next_position from ' . self::$tableName . ' where itemID = ?';
			$row = $this->db->select($query, 'i', array(&$this->itemID))[0];
			
			$this->position = $row['next_position'];
			$this->write();
		}
	}
}

?>