<?php

class StyleguideIconTableItemDraggableModel extends DraggablesModel
{
	public function getData()
	{
		if ($this->id)
		{		
			$item = new StyleguideIconTableItem($this->db, $this->id);
			$item->read();
			$item->readItemData();
			
			$sections = [];
			foreach ($item->listings as $index=>$listing)
			{
				$draggableSection = new DraggableSection($listing->id, $listing->html, true, $index + 1);
				$sections []= $draggableSection;
			}
			
			return $sections;
		}
		else
		{
			throw new Exception('Item ID must be set');
		}
	}
	
	public function arrangeSections()
	{
		$this->arrangeSectionsSimple('StyleguideIconTableListing');
	}
}

?>