<?php

class StyleguideColorDescriptorItemModel extends StyleguideColorItemModel
{
	public function getData()
	{
		$colors = $this->getColorAndVariants();
		
		$colorDescriptors = [];
		$colorDescriptors []= $colors->name;
		
		$rows = $this->db->select('select description from sg_color_descriptor where itemID = ? order by position', 'i', [&$this->foreignItem->itemID]);
		foreach ($rows as $row)
		{
			$colorDescriptors []= $row['description'];
		}
		
		return new ColorDescriptorItem($colors, $colorDescriptors);
	}
}

?>