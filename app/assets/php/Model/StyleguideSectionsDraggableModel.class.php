<?php

class StyleguideSectionsDraggableModel extends DraggablesModel
{
	public function getData()
	{
		$sections = [];
		$rows = $this->db->select('select id from sg_section order by position');
		foreach ($rows as $index=>$row)
		{
			$section = new StyleguideSection($this->db, $row['id']);
			$section->read();
			
			$draggableSection = new DraggablesSection($section->id, $section->name, $section->enabled, $index + 1);
			$sections []= $draggableSection;
		}
		
		return $sections;
	}
	
	public function arrangeSections()
	{
		$this->arrangeSectionsSimple('StyleguideSection');
	}
}

?>