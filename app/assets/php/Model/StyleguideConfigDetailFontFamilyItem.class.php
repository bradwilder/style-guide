<?php

class StyleguideConfigDetailFontFamilyItem
{
	public $id;
	public $name;
	public $alphabet;
	
	public function __construct($id, $name, $alphabet)
	{
		$this->id = $id;
		$this->name = $name;
		$this->alphabet = $alphabet;
	}
}

?>