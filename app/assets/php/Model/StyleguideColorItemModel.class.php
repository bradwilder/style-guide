<?php

class StyleguideColorItemModel extends StyleguideItemModel
{
	public function getData()
	{
		return $this->getColorAndVariants();
	}
	
	public function getConfigData()
	{
		if ($this->foreignID)
		{
			$colorItem = new StyleguideColorItem($this->db, $this->foreignID);
			$colorItem->read();
			$colorItem->readItemData();
			
			return new StyleguideConfigDetailColorDescriptorItem($colorItem->colors, $colorItem->descriptors);
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
	
	protected function getColorAndVariants()
	{
		if ($this->foreignID)
		{
			$row = $this->db->select('select c.name, c.hex, c.variant1, c.variant2, it.code from sg_color c join sg_color_item ci on ci.color1ID = c.id join sg_item i on i.id = ci.baseID join sg_item_type it on it.id = i.typeID where ci.baseID = ?', 'i', [&$this->foreignID])[0];
			
			$colorItem = new ColorItem($row['name'], $row['hex']);
			if (strpos($row['code'], 'var'))
			{
				$variant1 = $row['variant1'];
				if ($variant1)
				{
					$colorItem->addShade($variant1);
				}
				
				$variant2 = $row['variant2'];
				if ($variant2)
				{
					$colorItem->addShade($variant2);
				}
			}
			
			return $colorItem;
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
}

?>