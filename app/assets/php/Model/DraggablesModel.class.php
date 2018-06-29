<?php

abstract class DraggablesModel extends SimpleModel
{
	public $id;
	public $sections;
	
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
}

?>