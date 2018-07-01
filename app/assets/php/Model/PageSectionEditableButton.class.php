<?php

class PageSectionEditableButton
{
	public $edit = false;
	public $classes;
	public $attributes;
	
	public function __construct($classes, $attributes)
	{
		$this->classes = $classes;
		$this->attributes = $attributes;
	}
}

?>