<?php

class StyleguideFontTableListing extends DBItemPositioned
{
	public $text;
	public $itemID;
	public $fontID;
	
	// Extra properties
	public $cssList = array();
	public $font;
	
	private static $tableName = 'sg_font_listing';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('text', new DBColumn(DBColumnType::String));
		$this->addColumn('itemID', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('fontID', new DBColumn(DBColumnType::Numeric));
	}
	
	public function readExtra()
	{
		$query = 'select id from sg_font_listing_css where fontListingID = ?';
		$rows = $this->db->select($query, 'i', array(&$this->id));
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