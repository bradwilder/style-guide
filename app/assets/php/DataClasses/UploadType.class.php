<?php

class UploadType extends DBItem
{
	public $code;
	
	private static $tableName = 'sg_upload_type';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$tableName);
	}
	
	public function write()
	{
		$this->writeBase($this->code, 'code', self::$tableName, true);
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