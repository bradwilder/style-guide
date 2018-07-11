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
		parent::__construct($db, self::$tableName, $id);
	}
	
	public function write()
	{
		$this->writeBase($this->typeID, 'typeID');
		$this->writeBase($this->code, 'code', true);
		$this->writeBase($this->minLG, 'minLG');
		$this->writeBase($this->minMD, 'minMD');
		$this->writeBase($this->minSM, 'minSM');
		$this->writeBase($this->minXS, 'minXS');
	}
}

?>