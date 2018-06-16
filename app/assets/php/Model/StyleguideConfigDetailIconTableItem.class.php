<?php

class StyleguideConfigDetailIconTableItem
{
	public $iconSet;
	public $listings = array();
	
	public function __construct($iconSet)
	{
		$this->iconSet = $iconSet;
	}
	
	public function addListing($listing)
	{
		$this->listings []= $listing;
	}
}

?>