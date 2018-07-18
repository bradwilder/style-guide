<?php

class StyleguideConfigSubsectionModel extends Model_base
{
	public function deleteSubsection(int $id)
	{
		$subsection = new StyleguideSubsection($this->db, $id);
		$subsection->delete();
	}
	
	public function nameExists(string $name, int $id, int $sectionID, int $parentID)
	{
		if ($id)
		{
			return StyleguideSubsection::nameExistsEdit($this->db, $name, $id);
		}
		else
		{
			return StyleguideSubsection::nameExistsNew($this->db, $name, $sectionID, $parentID);
		}
	}
	
	public function getSubsection(int $id)
	{
		$subsection = new StyleguideSubsection($this->db, $id);
		$subsection->read();
		return $subsection;
	}
	
	public function editSubsection(int $id, string $name, string $description)
	{
		$subsection = new StyleguideSubsection($this->db, $id);
		$subsection->name = $name;
		$subsection->description = $description;
		$subsection->write();
	}
	
	public function enableSubsection(int $id, bool $enabled)
	{
		$subsection = new StyleguideSubsection($this->db, $id);
		$subsection->enabled = $enabled;
		$subsection->write();
	}
	
	public function addSubsection(string $name, string $description, bool $enabled, int $sectionID, int $parentID)
	{
		$subsection = new StyleguideSubsection($this->db, null, $sectionID, $parentID);
		$subsection->name = $name;
		$subsection->description = $description;
		$subsection->enabled = $enabled;
		$subsection->write();
	}
}

?>