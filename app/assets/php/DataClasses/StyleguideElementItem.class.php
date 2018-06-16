<?php

class StyleguideElementItem extends StyleguideItem
{
	public $upload1ID;
	public $upload2ID;
	public $upload3ID;
	public $upload4ID;
	public $upload5ID;
	public $upload6ID;
	
	// Extra properties
	public $uploads = array();
	
	private static $tableName = 'sg_element';
	private static $code = 'elem-seg';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$code, self::$tableName);
	}
	
	public function write()
	{
		parent::write();
		
		$this->writeSub($this->upload1ID, 'upload1ID', self::$tableName, false, true);
		$this->writeSub($this->upload2ID, 'upload2ID', self::$tableName, false, true);
		$this->writeSub($this->upload3ID, 'upload3ID', self::$tableName, false, true);
		$this->writeSub($this->upload4ID, 'upload4ID', self::$tableName, false, true);
		$this->writeSub($this->upload5ID, 'upload5ID', self::$tableName, false, true);
		$this->writeSub($this->upload6ID, 'upload6ID', self::$tableName, false, true);
	}
	
	public function read()
	{
		parent::read(self::$tableName);
	}
	
	public function readItemData()
	{
		if ($this->upload1ID)
		{
			$upload = new Upload($this->db, $this->upload1ID);
			$upload->read();
			$this->uploads []= $upload;
		}
		
		if ($this->upload2ID)
		{
			$upload = new Upload($this->db, $this->upload2ID);
			$upload->read();
			$this->uploads []= $upload;
		}
		
		if ($this->upload3ID)
		{
			$upload = new Upload($this->db, $this->upload3ID);
			$upload->read();
			$this->uploads []= $upload;
		}
		
		if ($this->upload4ID)
		{
			$upload = new Upload($this->db, $this->upload4ID);
			$upload->read();
			$this->uploads []= $upload;
		}
		
		if ($this->upload5ID)
		{
			$upload = new Upload($this->db, $this->upload5ID);
			$upload->read();
			$this->uploads []= $upload;
		}
		
		if ($this->upload6ID)
		{
			$upload = new Upload($this->db, $this->upload6ID);
			$upload->read();
			$this->uploads []= $upload;
		}
	}
}

?>