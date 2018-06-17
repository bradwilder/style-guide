<?php

class MoodboardSectionImage extends DBItem
{
	public $sectionID;
	public $imageID;
	public $position;
	public $sizeID;
	
	// Extra properties
	public $size;
	
	private static $tableName = 'mb_section_image';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->sectionID, 'sectionID', self::$tableName);
		$this->writeBase($this->imageID, 'imageID', self::$tableName);
		$this->writeBase($this->position, 'position', self::$tableName);
		$this->writeBase($this->sizeID, 'sizeID', self::$tableName);
	}
	
	public function read()
	{
		parent::readBase();
	}
	
	public function readExtra()
	{
		if ($this->sizeID)
		{
			$size = new MoodboardSectionImageSize($this->db, $this->sizeID);
			$size->read();
			$this->size = $size;
		}
	}
	
	public function delete()
	{
		parent::deleteBase();
	}
	
	public function writePosition()
	{
		if ($this->sectionID)
		{
			$query = 'select case when max(position) is not null then max(position) + 1 else 1 end as next_position from ' . self::$tableName . ' where sectionID = ?';
			$row = $this->db->select($query, 'i', array(&$this->sectionID))[0];
			
			$this->position = $row['next_position'];
			$this->write();
		}
	}
}

?>