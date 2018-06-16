<?php

abstract class DraggablesModel extends Model_base
{
	public $id;
	public $sections;
	
	public abstract function getSections();
	public abstract function arrangeSections();
	
	protected function arrangeSectionsSimple($className)
	{
		if ($this->sections)
		{
			foreach ($this->sections as $sectionStr)
			{
				$section = new $className($this->db, $sectionStr[0]);
				$section->read();
				$section->position = $sectionStr[1];
				$section->write();
			}
		}
		else
		{
			throw new Exception('Sections must be set');
		}
	}
	
	protected static function createDraggablesSection($id, $name, $enabled, $position, $description = null)
	{
		$draggableSection = new DraggableSection();
		$draggableSection->id = $id;
		$draggableSection->name = $name;
		$draggableSection->description = $description;
		$draggableSection->enabled = $enabled;
		$draggableSection->position = $position;
		return $draggableSection;
	}
}

?>