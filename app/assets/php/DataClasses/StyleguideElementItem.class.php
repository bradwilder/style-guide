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
		
		$this->addSubColumn('upload1ID', new DBColumn(DBColumnType::Numeric, true));
		$this->addSubColumn('upload2ID', new DBColumn(DBColumnType::Numeric, true));
		$this->addSubColumn('upload3ID', new DBColumn(DBColumnType::Numeric, true));
		$this->addSubColumn('upload4ID', new DBColumn(DBColumnType::Numeric, true));
		$this->addSubColumn('upload5ID', new DBColumn(DBColumnType::Numeric, true));
		$this->addSubColumn('upload6ID', new DBColumn(DBColumnType::Numeric, true));
	}
	
	public function read()
	{
		parent::read(true);
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