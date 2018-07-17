<?php

class StyleguideColorDescriptorItemModel extends StyleguideColorItemModel
{
	public function getData()
	{
		$color = $this->getColorAndVariants();
		
		$colorDescriptors = [];
		$colorDescriptors []= $color->name;
		
		$rows = $this->db->select('select description from sg_color_descriptor where itemID = ? order by position', 'i', [&$this->foreignItem->itemID]);
		foreach ($rows as $row)
		{
			$colorDescriptors []= $row['description'];
		}
		
		return new ColorDescriptorItem($color, $colorDescriptors);
	}
}

?>