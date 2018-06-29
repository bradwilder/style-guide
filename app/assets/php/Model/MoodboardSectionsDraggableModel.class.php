<?php

class MoodboardSectionsDraggableModel extends DraggablesModel
{
	public function getData()
	{
		$rows = $this->db->select('select id from mb_section order by position');
		
		$sections = array();
		foreach ($rows as $index=>$row)
		{
			$id = $row['id'];
			
			$section = new MoodboardSection($this->db, $id);
			$section->read();
			$section->readExtra();
			
			$draggableSection = new DraggablesSection($section->id, $section->name, true, $index + 1, count($section->images) . ' image' . (count($section->images) == 1 ? '' : 's'));
			$sections []= $draggableSection;
		}
		
		return $sections;
	}
	
	public function arrangeSections()
	{
		$this->arrangeSectionsSimple('MoodboardSection');
	}
}

?>