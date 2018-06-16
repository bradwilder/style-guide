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
	}
	
	public function write()
	{
		parent::write();
		
		$this->writeSub($this->importURL, 'importURL', self::$tableName, true);
		$this->writeSub($this->website, 'website', self::$tableName, true, true);
	}
	
	public function read()
	{
		parent::read(self::$tableName);
	}
}

?>