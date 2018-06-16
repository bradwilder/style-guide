<?php

class PageOptionItem
{
	public $code;
	public $value;
	
	public function __construct($code, $value)
	{
		$this->code = $code;
		$this->value = $value;
	}
}

?>