<?php

class StyleguideConfigTreeItem
{
	public $id;
	public $type;
	public $name;
	public $enabled;
	public $children = [];
	
	public function __construct($id, $type, $text, $substringPrefix = null, $enabled = true)
	{
		$this->id = $id;
		$this->type = $type;
		$this->name = $this->getPrefix($substringPrefix) . $text;
		$this->enabled = ($enabled === true || $enabled === '1');
	}
	
	private function getPrefix($substringPrefix)
	{
		switch ($this->type)
		{
			case "Section":
				return '§ ';
			case "Subsection":
				return $substringPrefix . '. ';
			case "Item":
				return '';
			default:
				return '';
		}
	}
	
	public function addChild($child)
	{
		$this->children []= $child;
	}
}

?>