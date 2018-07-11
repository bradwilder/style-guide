<?php

class StyleguideFontFamilyItem extends StyleguideItem
{
	public $fontID;
	
	private static $tableName = 'sg_font_family';
	private static $code = 'font-fmy';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$code, self::$tableName);
	}
	
	public function write()
	{
		parent::write();
		
		$this->writeSub($this->fontID, 'fontID');
	}
	
	public function read()
	{
		parent::read(true);
	}
	
	public function readItemData()
	{
		
	}
}

?>