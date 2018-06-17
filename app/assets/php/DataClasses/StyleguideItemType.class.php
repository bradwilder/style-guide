<?php

class StyleguideItemType extends DBItem
{
	public $code;
	public $description;
	
	private static $tableName = 'sg_item_type';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->code, 'code', true);
		$this->writeBase($this->description, 'description', true, true);
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