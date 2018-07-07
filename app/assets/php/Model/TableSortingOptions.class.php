<?php

class TableSortingOptions
{
	public $initialCol;
	public $initialOrder;
	
	public function __construct($initialCol = 0, $initialOrder = 0)
	{
		$this->initialCol = $initialCol;
		$this->initialOrder = $initialOrder;
	}
}

?>