<?php

class ForeignItem
{
	public $itemID;
	public $code;
	
	public function __construct($itemID, $code)
	{
		$this->itemID = $itemID;
		$this->code = $code;
	}
}

?>