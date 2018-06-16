<?php

class StyleguideConfigDetailElementItem
{
	public $images;
	
	public function __construct()
	{
		$this->images = array();
	}
	
	public function addImage($image)
	{
		$this->images []= $image;
	}
}

?>