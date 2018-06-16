<?php

class StyleguideFontTableItem extends StyleguideItem
{
	// Extra propertes
	public $listings = array();
	
	private static $tableName = 'sg_font_listing_table';
	private static $code = 'font-tbl';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$code, self::$tableName);
	}
	
	public function readItemData()
	{
		$query = 'select id from sg_font_listing where itemID = ? order by position';
		$rows = $this->db->select($query, 'i', array(&$this->id));
		foreach ($rows as $row)
		{
			$fontListing = new StyleguideFontTableListing($this->db, $row['id']);
			$fontListing->read();
			$fontListing->readExtra();
			
			$this->listings []= $fontListing;
		}
	}
}

?>