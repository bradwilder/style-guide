<?php

class StyleguideConfigSubsectionModel extends Model_base
{
	public $id;
	public $sectionID;
	public $parentID;
	public $name;
	public $description;
	public $enabled;
	
	public function deleteSubsection()
	{
		if ($this->id)
		{
			$subsection = new StyleguideSubsection($this->db, $this->id);
			$subsection->delete();
		}
		else
		{
			throw new Exception('Subsection ID must be set');
		}
	}
	
	public function nameExists()
	{
		if ($this->name)
		{
			if ($this->id)
			{
				return StyleguideSubsection::nameExistsEdit($this->name, $this->id);
			}
			else
			{
				return StyleguideSubsection::nameExistsNew($this->name, $this->sectionID, $this->parentID);
			}
		}
		else
		{
			throw new Exception('Subsection name must be set');
		}
	}
	
	public function getSubsection()
	{
		if ($this->id)
		{
			$subsection = new StyleguideSubsection($this->db, $this->id);
			$subsection->read();
			return $subsection;
		}
		else
		{
			throw new Exception('Subsection ID must be set');
		}
	}
	
	public function editSubsection()
	{
		if ($this->id)
		{
			$subsection = new StyleguideSubsection($this->db, $this->id);
			$subsection->name = $this->name;
			$subsection->description = $this->description;
			$subsection->write();
		}
		else
		{
			throw new Exception('Subsection id must be set');
		}
	}
	
	public function enableSubsection()
	{
		if ($this->id &&isset($this->enabled))
		{
			$subsection = new StyleguideSubsection($this->db, $this->id);
			$subsection->enabled = $this->enabled;
			$subsection->write();
		}
		else
		{
			throw new Exception('Subsection id and enabled value must be set');
		}
	}
	
	public function addSubsection()
	{
		if ($this->name)
		{
			$subsection = new StyleguideSubsection($this->db, null, $this->sectionID, $this->parentID);
			$subsection->name = $this->name;
			$subsection->description = $this->description;
			$subsection->enabled = $this->enabled;
			$subsection->write();
		}
		else
		{
			throw new Exception('Subsection name must be set');
		}
	}
}

?>