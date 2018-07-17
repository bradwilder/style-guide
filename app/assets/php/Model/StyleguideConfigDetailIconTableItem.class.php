<?php

class StyleguideConfigDetailIconTableItem
{
	public $iconSet;
	public $listings = [];
	
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