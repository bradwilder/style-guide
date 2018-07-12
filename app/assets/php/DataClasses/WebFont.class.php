<?php

class WebFont extends Font
{
	public $importURL;
	public $website;
	
	private static $tableName = 'sg_webfont';
	public static $code = 'web';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$code, self::$tableName);
		
		$this->addSubColumn('importURL', new DBColumn(DBColumnType::String));
		$this->addSubColumn('website', new DBColumn(DBColumnType::String, true));
	}
	
	public function read()
	{
		parent::read(true);
	}
}

?>