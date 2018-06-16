<?php

class FontFamilyItem
{
	public $name;
	public $alphabet;
	public $typeCode;
	public $url;
	public $import;
	public $cssFile;
	
	public function __construct($name, $alphabet, $typeCode)
	{
		$this->name = $name;
		$this->alphabet = $alphabet;
		$this->typeCode = $typeCode;
	}
}

?>