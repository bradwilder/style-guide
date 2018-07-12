<?php

class CSSFont extends Font
{
	public $directory;
	public $cssFile;
	
	private static $tableName = 'sg_cssfont';
	public static $code = 'css';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$code, self::$tableName);
	}
	
	public function write()
	{
		parent::write();
		
		$this->writeSub($this->directory, 'directory', DBColumnType::String, true);
		$this->writeSub($this->cssFile, 'cssFile', DBColumnType::String);
	}
	
	public function read()
	{
		parent::read(true);
	}
}

?>