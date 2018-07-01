<?php

class PageSectionEditableOptions
{
	public $role;
	public $submittable;
	public $buttons = array();
	
	public function __construct($role, $submittable = false)
	{
		$this->role = $role;
		$this->submittable = $submittable;
	}
	
	public function addButton(PageSectionEditableButton $button)
	{
		$this->buttons []= $button;
	}
}

?>