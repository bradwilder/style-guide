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
		
		$this->addColumn('typeID', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('code', new DBColumn(DBColumnType::String));
		$this->addColumn('minLG', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('minMD', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('minSM', new DBColumn(DBColumnType::Numeric));
		$this->addColumn('minXS', new DBColumn(DBColumnType::Numeric));
	}
}

?>