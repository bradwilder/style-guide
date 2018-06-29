<?php

class StyleguideSubSubsectionsDraggableModel extends StyleguideSubsectionsDraggableModel
{
	public function getSections()
	{
		if ($this->id)
		{
			$subsections = array();
			$rows = $this->db->select('select id from sg_subsection where parentSubsectionID = ? order by position', 'i', array(&$this->id));
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
			throw new Exception('Parent subsection id must be set');
		}
	}
}

?>