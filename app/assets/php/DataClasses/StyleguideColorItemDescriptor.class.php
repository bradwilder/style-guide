<?php

class StyleguideColorItemDescriptor extends DBItem
{
	public $description;
	public $itemID;
	public $position;
	
	private static $tableName = 'sg_color_descriptor';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->description, 'description', self::$tableName, true);
		$this->writeBase($this->itemID, 'itemID', self::$tableName);
		$this->writeBase($this->position, 'position', self::$tableName);
	}
	
	public function read()
	{
		parent::readBase();
	}
	
	public function delete()
	{
		parent::deleteBase();
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