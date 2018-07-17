<?php

class StyleguideConfigDetailFontTableListing
{
	public $id;
	public $sampleText;
	public $cssList = [];
	
	public function __construct($id, $sampleText)
	{
		$this->id = $id;
		$this->sampleText = $sampleText;
	}
	
	public function addCSS($css)
	{
		$this->cssList []= $css;
	}
}

?>