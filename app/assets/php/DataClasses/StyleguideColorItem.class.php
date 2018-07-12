<?php

class StyleguideColorItem extends StyleguideItem
{
	public $color1ID;
	public $color2ID;
	public $color3ID;
	public $color4ID;
	public $color5ID;
	public $color6ID;
	
	// Extra properties
	public $colors = array();
	public $descriptors = array();
	
	private static $tableName = 'sg_color_item';
	
	public function __construct(Db $db, int $id = null, $code = null)
	{
		if (!$id && !$code)
		{
			return;
		}
		
		parent::__construct($db, $id, $code, self::$tableName);
		
		$this->addSubColumn('color1ID', new DBColumn(DBColumnType::Numeric, true));
		$this->addSubColumn('color2ID', new DBColumn(DBColumnType::Numeric, true));
		$this->addSubColumn('color3ID', new DBColumn(DBColumnType::Numeric, true));
		$this->addSubColumn('color4ID', new DBColumn(DBColumnType::Numeric, true));
		$this->addSubColumn('color5ID', new DBColumn(DBColumnType::Numeric, true));
		$this->addSubColumn('color6ID', new DBColumn(DBColumnType::Numeric, true));
	}
	
	public function read()
	{
		parent::read(true);
	}
	
	public function readItemData()
	{
		if ($this->color1ID)
		{
			$color = new Color($this->db, $this->color1ID);
			$color->read();
			$this->colors []= $color;
		}
		
		if ($this->color2ID)
		{
			$color = new Color($this->db, $this->color2ID);
			$color->read();
			$this->colors []= $color;
		}
		
		if ($this->color3ID)
		{
			$color = new Color($this->db, $this->color3ID);
			$color->read();
			$this->colors []= $color;
		}
		
		if ($this->color4ID)
		{
			$color = new Color($this->db, $this->color4ID);
			$color->read();
			$this->colors []= $color;
		}
		
		if ($this->color5ID)
		{
			$color = new Color($this->db, $this->color5ID);
			$color->read();
			$this->colors []= $color;
		}
		
		if ($this->color6ID)
		{
			$color = new Color($this->db, $this->color6ID);
			$color->read();
			$this->colors []= $color;
		}
		
		$query = 'select id from sg_color_descriptor where itemID = ? order by position';
		$rows = $this->db->select($query, 'i', array(&$this->id));
		foreach ($rows as $row)
		{
			$descriptor = new StyleguideColorItemDescriptor($this->db, $row['id']);
			$descriptor->read();
			$this->descriptors []= $descriptor;
		}
	}
}

?>