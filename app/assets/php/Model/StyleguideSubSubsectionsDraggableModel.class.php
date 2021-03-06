<?php

class StyleguideSubSubsectionsDraggableModel extends StyleguideSubsectionsDraggableModel
{
	public function getData()
	{
		if ($this->id)
		{
			$subsections = [];
			$rows = $this->db->select('select id from sg_subsection where parentSubsectionID = ? order by position', 'i', [&$this->id]);
			foreach ($rows as $index=>$row)
			{
				$subsection = new StyleguideSubsection($this->db, $row['id']);
				$subsection->read();
				
				$draggableSection = new DraggableSection($subsection->id, $subsection->name, $subsection->enabled, $index + 1);
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