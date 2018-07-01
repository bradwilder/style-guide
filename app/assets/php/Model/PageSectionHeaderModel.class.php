<?php

class PageSectionHeaderModel extends Model_base
{
	public $title;
	public $editableOptions;
	public $buttons;
	
	public function __construct($title, PageSectionEditableOptions $editableOptions = null, $buttons = null)
	{
		$this->title = $title;
		$this->editableOptions = $editableOptions;
		$this->buttons = $buttons;
	}
}

?>