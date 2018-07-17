<?php

class StyleguideSubsectionsDraggableModel extends DraggablesModel
{
	public function getData()
	{
		if ($this->id)
		{
			$subsections = [];
			$rows = $this->db->select('select id from sg_subsection where sectionID = ? and parentSubsectionID is null order by position', 'i', [&$this->id]);
			foreach ($rows as $index=>$row)
			{
				$subsection = new StyleguideSubsection($this->db, $row['id']);
				$subsection->read();
				
				$draggableSection = new DraggablesSection($subsection->id, $subsection->name, $subsection->enabled, $index + 1);
				$subsections []= $draggableSection;
			}
			
			return $subsections;
		}
		else
		{
			throw new Exception('Parent section id must be set');
		}
	}
	
	public function arrangeSections()
	{
		$this->arrangeSectionsSimple('StyleguideSubsection');
	}
}

?>