<?php

class ColorItem
{
	public $name;
	public $hex;
	public $shades;
	
	public function __construct($name, $hex, $shades)
	{
		$this->name = $name;
		$this->hex = $hex;
		$this->shades = $shades;
	}
}

?>