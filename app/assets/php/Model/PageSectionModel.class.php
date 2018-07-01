<?php

class PageSectionModel extends Model_base
{
	public $title;
	public $description;
	public $content;
	public $subsections;
	public $editableOptions;
	public $headerButtons;
	
	public $sectionModel;
	
	public function __construct(Model_base $sectionModel = null, PageSectionEditableOptions $editableOptions = null, $headerButtons = null)
	{
		$this->sectionModel = $sectionModel;
		$this->editableOptions = $editableOptions;
		$this->headerButtons = $headerButtons;
	}
}

?>