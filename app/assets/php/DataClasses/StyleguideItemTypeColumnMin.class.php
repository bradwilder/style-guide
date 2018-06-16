<?php

class StyleguideItemTypeColumnMin extends DBItem
{
	public $typeID;
	public $code;
	public $minLG;
	public $minMD;
	public $minSM;
	public $minXS;
	private static $tableName = 'sg_item_type_column_min';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->typeID, 'typeID', self::$tableName);
		$this->writeBase($this->code, 'code', self::$tableName, true);
		$this->writeBase($this->minLG, 'minLG', self::$tableName);
		$this->writeBase($this->minMD, 'minMD', self::$tableName);
		$this->writeBase($this->minSM, 'minSM', self::$tableName);
		$this->writeBase($this->minXS, 'minXS', self::$tableName);
	}
	
	public function read()
	{
		parent::readBase(self::$tableName);
	}
	
	public function delete()
	{
		parent::deleteBase(self::$tableName);
	}
}

?>