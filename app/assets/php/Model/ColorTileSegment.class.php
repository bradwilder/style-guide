<?php

class ColorTileSegment
{
	public $name;
	public $hex;
	public $isMainColor;
	
	public function __construct($name, $hex, $isMainColor)
	{
		$this->name = $name;
		$this->hex = $hex;
		$this->isMainColor = $isMainColor;
	}
}

?>