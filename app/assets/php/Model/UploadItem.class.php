<?php

class UploadItem
{
	public $id;
	public $name;
	public $folder;
	public $children;
	
	public function __construct($id, $name, $folder)
	{
		$this->id = $id;
		$this->name = $name;
		$this->folder = ($folder === true || $folder == '1');
		$this->children = array();
	}
	
	public function addChild($child)
	{
		$this->children []= $child;
	}
}

?>