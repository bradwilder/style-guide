<?php

class StyleguideSectionsModel extends Model_base
{
	public $enabled;
	
	public function getSections()
	{
		$sections = [];
		$rows = $this->db->select('select id from sg_section ' . (isset($this->enabled) ? 'where enabled = ' . ($this->enabled ? 1 : 0) : '') . ' order by position');
		foreach ($rows as $row)
		{
			$section = new StyleguideSection($this->db, $row['id']);
			$section->read();
			$section->readExtra($this->enabled);
			
			$sections []= $section;
		}
		
		return $sections;
	}
}

?>