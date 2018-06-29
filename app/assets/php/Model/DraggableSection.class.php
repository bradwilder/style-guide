<?php

class DraggableSection
{
	public $id;
	public $name;
	public $description;
	public $enabled;
	public $position;
	
	public function __construct($id, $name, $enabled, $position, $description = null)
	{
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->enabled = $enabled;
		$this->position = $position;
	}
}

?>