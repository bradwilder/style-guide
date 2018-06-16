<?php

class StyleguideConfigDetailItem
{
	public $id;
	public $name;
	public $type;
	public $item;
	public $columns;
	
	public function __construct($id, $name, $type, $item, $columns)
	{
		$this->id = $id;
		$this->name = $name;
		$this->type = $type;
		$this->item = $item;
		$this->columns = $columns;
	}
}

?>