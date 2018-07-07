<?php

class TableModel extends Model_base
{
	public $sortableOptions;
	public $striped;
	public $selectable;
	public $attributes;
	public $rowTemplate;
	public $data;
	public $columns = array();
	
	public function __construct(TableSortingOptions $sortableOptions = null, $striped = false, $selectable = false, string $rowTemplate, $data = null, $attributes = null)
	{
		$this->sortableOptions = $sortableOptions;
		$this->striped = $striped;
		$this->selectable = $selectable;
		$this->attributes = $attributes;
		$this->rowTemplate = $rowTemplate;
		$this->data = $data;
	}
	
	public function addTableHeaderColumn(TableHeaderColumnModel $column)
	{
		$this->columns []= $column;
	}
}

?>