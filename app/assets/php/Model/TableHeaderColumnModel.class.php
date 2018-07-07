<?php

class TableHeaderColumnModel extends Model_base
{
	public $tableSortable = false;
	public $columnSortable = false;
	public $data;
	public $cog = false;
	public $attributes;
	
	public function __construct($data, $attributes = null, $tableSortable = false, $columnSortable = false, $cog = false)
	{
		$this->data = $data;
		$this->attributes = $attributes;
		$this->tableSortable = $tableSortable;
		$this->columnSortable = $columnSortable;
		$this->cog = $cog;
	}
}

?>