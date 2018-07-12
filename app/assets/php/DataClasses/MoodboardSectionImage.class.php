<?php

class MoodboardSectionImage extends DBItem
{
	public $sectionID;
	public $imageID;
	public $sizeID;
	public $position;
	
	// Extra properties
	public $size;
	
	private static $tableName = 'mb_section_image';
	
	public function __construct(Db $db, int $id = null, int $sectionID = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('sectionID', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('imageID', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('sizeID', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('position', new DBColumn(DBColumnType::Numeric));
		
		$this->sectionID = $sectionID;
		
		if (!$id && $sectionID)
		{
			$this->writePosition();
		}
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
	
	private function writePosition()
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