<?php

class ColorItem
{
	public $name;
	public $hex;
	public $shades = [];
	
	public function __construct(string $name, string $hex)
	{
		$this->name = $name;
		$this->hex = $hex;
	}
	
	public function addShade(string $shade)
	{
		$this->shades []= $shade;
	}
}

?>