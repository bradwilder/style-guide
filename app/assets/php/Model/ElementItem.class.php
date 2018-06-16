<?php

class ElementItem
{
	public $path;
	public $file;
	public $shortName;
	public $fullName;
	
	public function __construct($path, $file, $shortName, $fullName)
	{
		$this->path = $path;
		$this->file = $file;
		$this->shortName = $shortName;
		$this->fullName = $fullName;
	}
}

?>