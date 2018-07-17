<?php

class ColorDescriptorItem
{
	public $color;
	public $descriptors;
	
	public function __construct($color, $descriptors)
	{
		$this->color = $color;
		$this->descriptors = $descriptors;
	}
}

?>