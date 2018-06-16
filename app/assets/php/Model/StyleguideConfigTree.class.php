<?php

class StyleguideConfigTree
{
	public $children = array();
	
	public function addChild($child)
	{
		$this->children []= $child;
	}
}

?>