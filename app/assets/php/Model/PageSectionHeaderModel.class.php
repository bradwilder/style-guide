<?php

class PageSectionHeaderModel extends Model_base
{
	public $title;
	public $editableOptions;
	
	public function __construct($title, PageSectionEditableOptions $editableOptions = null)
	{
		$this->title = $title;
		$this->editableOptions = $editableOptions;
	}
}

?>