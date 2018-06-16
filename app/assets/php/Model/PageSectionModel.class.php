<?php

class PageSectionModel extends Model_base
{
	public $title;
	public $description;
	public $content;
	public $subsections;
	
	public $sectionModel;
	
	public function __construct(Model_base $sectionModel)
	{
		$this->sectionModel = $sectionModel;
	}
}

?>