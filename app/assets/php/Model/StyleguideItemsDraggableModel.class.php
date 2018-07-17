<?php

class StyleguideItemsDraggableModel extends DraggablesModel
{
	public function getData()
	{
		if ($this->id)
		{
			$items = [];
			$rows = $this->db->select('select id from sg_item where subsectionID = ? order by position', 'i', [&$this->id]);
			foreach ($rows as $index=>$row)
			{
				$item = new StyleguideItem($this->db, $row['id']);
				$item->read();
				
				$draggableSection = new DraggableSection($item->id, $item->name, true, $index + 1);
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