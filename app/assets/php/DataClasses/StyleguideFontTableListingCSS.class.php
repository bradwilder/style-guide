<?php

class StyleguideFontTableListingCSS extends DBItem
{
	public $css;
	public $fontListingID;
	
	private static $tableName = 'sg_font_listing_css';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->css, 'css', self::$tableName, true);
		$this->writeBase($this->fontListingID, 'fontListingID', self::$tableName);
	}
	
	public function read()
	{
		parent::readBase();
	}
	
	public function delete()
	{
		parent::deleteBase();
	}
}

?>