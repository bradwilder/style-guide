<?php

class StyleguideFontTableListingCSS extends DBItem
{
	public $css;
	public $fontListingID;
	
	private static $tableName = 'sg_font_listing_css';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('css', new DBColumn(DBColumnType::String));
		$this->addColumn('fontListingID', new DBColumn(DBColumnType::Numeric));
	}
}

?>