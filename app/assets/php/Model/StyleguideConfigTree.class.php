<?php

class StyleguideConfigTree
{
	public $children = [];
	
	public function addChild($child)
	{
		$this->children []= $child;
	}
}

?>