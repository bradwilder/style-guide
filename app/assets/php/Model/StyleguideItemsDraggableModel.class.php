<?php

class StyleguideItemsDraggableModel extends DraggablesModel
{
	public function getData()
	{
		if ($this->id)
		{
			$items = array();
			$rows = $this->db->select('select id from sg_item where subsectionID = ? order by position', 'i', array(&$this->id));
			foreach ($rows as $index=>$row)
			{
				$item = new StyleguideItem($this->db, $row['id']);
				$item->read();
				
				$draggableSection = self::createDraggablesSection($item->id, $item->name, true, $index + 1);
				$items []= $draggableSection;
			}
			
			return $items;
		}
		else
		{
			throw new Exception('Parent subsection id must be set');
		}
	}
	
	public function arrangeSections()
	{
		$this->arrangeSectionsSimple('StyleguideItem');
	}
}

?>