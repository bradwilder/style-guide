<?php

class PageSubsection
{
	public $title;
	public $description;
	public $content = '';
	public $subSubsections = [];
	
	public function __construct($title, $description)
	{
		$this->title = $title;
		$this->description = $description;
	}
}

?>