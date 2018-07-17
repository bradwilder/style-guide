<?php

class StyleguideColorItemModel extends StyleguideItemModel
{
	public function getData()
	{
		return $this->getColorAndVariants();
	}
	
	protected function getColorAndVariants()
	{
		$row = $this->db->select('select c.name, c.hex, c.variant1, c.variant2 from sg_color c join sg_color_item ci on ci.color1ID = c.id where ci.baseID = ?', 'i', [&$this->foreignItem->itemID])[0];
		
		$shades = [];
		if (strpos($this->foreignItem->code, 'var'))
		{
			$variant1 = $row['variant1'];
			if ($variant1)
			{
				$shades []= $variant1;
			}
			
			$variant2 = $row['variant2'];
			if ($variant2)
			{
				$shades []= $variant2;
			}
		}
		
		return new ColorItem($row['name'], $row['hex'], $shades);
	}
}

?>