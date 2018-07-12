<?php

class StyleguideIconTableItem extends StyleguideItem
{
	public $fontID;
	
	// Extra properties
	public $listings = array();
	
	private static $tableName = 'sg_icon_listing_table';
	private static $code = 'icons-css';
	
	public function __construct(Db $db, int $id = null, int $subsectionID = null)
	{
		parent::__construct($db, $id, self::$code, self::$tableName, $subsectionID);
		
		$this->addSubColumn('fontID', new DBColumn(DBColumnType::Numeric));
	}
	
	public function read()
	{
		parent::read(true);
	}
	
	public function readItemData()
	{
		$query = 'select id from sg_icon_listing where itemID = ? order by position';
		$rows = $this->db->select($query, 'i', array(&$this->id));
		foreach ($rows as $row)
		{
			$iconListing = new StyleguideIconTableListing($this->db, $row['id']);
			$iconListing->read();
			
			$this->listings []= $iconListing;
		}
	}
}

?>