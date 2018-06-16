<?php

class FontListingItem
{
	public $text;
	public $cssArray;
	
	public function __construct($text, $cssArray)
	{
		$this->text = $text;
		$this->cssArray = $cssArray;
	}
}

?>