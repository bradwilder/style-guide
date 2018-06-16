<?php

class StyleguideColorItemDescriptorsDraggableModel extends DraggablesModel
{
	public function getSections()
	{
		if ($this->id)
		{		
			$item = new StyleguideColorItem($this->db, $this->id);
			$item->read();
			$item->readItemData();
			
			$sections = array();
			foreach ($item->descriptors as $index=>$descriptor)
			{
				$draggableSection = self::createDraggablesSection($descriptor->id, $descriptor->description, true, $index + 1);
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
		$this->arrangeSectionsSimple('StyleguideColorItemDescriptor');
	}
}

?>