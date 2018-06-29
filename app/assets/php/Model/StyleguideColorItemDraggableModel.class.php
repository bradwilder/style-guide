<?php

class StyleguideColorItemDraggableModel extends DraggablesModel
{
	public function getData()
	{
		if ($this->id)
		{		
			$colorItem = new StyleguideColorItem($this->db, $this->id);
			$colorItem->read();
			$colorItem->readItemData();
			
			$sections = array();
			foreach ($colorItem->colors as $index=>$color)
			{
				$draggableSection = self::createDraggablesSection($color->id, $color->name, true, $index + 1);
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
		if ($this->sections && $this->id)
		{
			$colorItem = new StyleguideColorItem($this->db, $this->id);
			
			if ($this->sections[0])
			{
				$colorItem->color1ID = $this->sections[0][0];
			}

			if ($this->sections[1])
			{
				$colorItem->color2ID = $this->sections[1][0];
			}

			if ($this->sections[2])
			{
				$colorItem->color3ID = $this->sections[2][0];
			}

			if ($this->sections[3])
			{
				$colorItem->color4ID = $this->sections[3][0];
			}

			if ($this->sections[4])
			{
				$colorItem->color5ID = $this->sections[4][0];
			}

			if ($this->sections[5])
			{
				$colorItem->color6ID = $this->sections[5][0];
			}

			$colorItem->write();
		}
		else
		{
			throw new Exception('Sections and item ID must be set');
		}
	}
}

?>