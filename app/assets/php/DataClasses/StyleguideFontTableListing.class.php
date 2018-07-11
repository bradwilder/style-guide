<?php

class StyleguideFontTableListing extends DBItem
{
	public $text;
	public $itemID;
	public $fontID;
	public $position;
	
	// Extra properties
	public $cssList = array();
	public $font;
	
	private static $tableName = 'sg_font_listing';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
	}
	
	public function write()
	{
		$this->writeBase($this->text, 'text', true);
		$this->writeBase($this->itemID, 'itemID');
		$this->writeBase($this->fontID, 'fontID');
		$this->writeBase($this->position, 'position');
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