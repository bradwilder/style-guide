<?php

class StyleguideElementItemDraggableModel extends DraggablesModel
{
	public function getData()
	{
		if ($this->id)
		{		
			$elementItem = new StyleguideElementItem($this->db, $this->id);
			$elementItem->read();
			$elementItem->readItemData();
			
			$sections = array();
			foreach ($elementItem->uploads as $index=>$upload)
			{
				$draggableSection = new DraggablesSection($upload->id, $upload->filePath, true, $index + 1);
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
			$elementItem = new StyleguideElementItem($this->db, $this->id);
			
			if ($this->sections[0])
			{
				$elementItem->upload1ID = $this->sections[0][0];
			}

			if ($this->sections[1])
			{
				$elementItem->upload2ID = $this->sections[1][0];
			}

			if ($this->sections[2])
			{
				$elementItem->upload3ID = $this->sections[2][0];
			}

			if ($this->sections[3])
			{
				$elementItem->upload4ID = $this->sections[3][0];
			}

			if ($this->sections[4])
			{
				$elementItem->upload5ID = $this->sections[4][0];
			}

			if ($this->sections[5])
			{
				$elementItem->upload6ID = $this->sections[5][0];
			}

			$elementItem->write();
		}
		else
		{
			throw new Exception('Sections and item ID must be set');
		}
	}
}

?>