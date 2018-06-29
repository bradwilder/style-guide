<?php

class MoodboardSectionsModel extends SimpleModel
{
	public function getData()
	{
		$rows = $this->db->select('select id from mb_section order by position');
		
		$sections = array();
		foreach ($rows as $row)
		{
			$sectionID = $row['id'];
			
			$section = new MoodboardSection($this->db, $sectionID);
			$section->read();
			$section->readExtra();
			
			$sections []= $section;
		}
		
		return $sections;
	}
}

?>