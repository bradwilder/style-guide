<?php

class StyleguideFontTableListing extends DBItem
{
	public $text;
	public $itemID;
	public $fontID;
	public $position;
	
	// Extra properties
	public $cssList = [];
	public $font;
	
	private static $tableName = 'sg_font_listing';
	
	public function __construct(Db $db, int $id = null, int $itemID = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('text', new DBColumn(DBColumnType::String));
		$this->addColumn('itemID', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('fontID', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('position', new DBColumn(DBColumnType::Numeric));
		
		$this->itemID = $itemID;
		
		if (!$id && $itemID)
		{
			$this->writePosition();
		}
	}
	
	public function readExtra()
	{
		$query = 'select id from sg_font_listing_css where fontListingID = ?';
		$rows = $this->db->select($query, 'i', [&$this->id]);
		foreach ($rows as $row)
		{
			$fontListingCSS = new StyleguideFontTableListingCSS($this->db, $row['id']);
			$fontListingCSS->read();
			
			$this->cssList []= $fontListingCSS;
		}
		
		if ($this->fontID)
		{
			$font = new Font($this->db, $this->fontID);
			$font->read();
			$this->font = $font;
		}
	}
	
	private function writePosition()
	{
		if ($this->itemID)
		{
			$query = 'select case when max(position) is not null then max(position) + 1 else 1 end as next_position from ' . self::$tableName . ' where itemID = ?';
			$row = $this->db->select($query, 'i', [&$this->itemID])[0];
			
			$this->position = $row['next_position'];
			$this->write();
		}
	}
}

?>