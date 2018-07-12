<?php

class UploadType extends DBItem
{
	public $code;
	
	private static $tableName = 'sg_upload_type';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, self::$tableName, $id);
		
		$this->addColumn('code', new DBColumn(DBColumnType::String));
	}
}

?>