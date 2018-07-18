<?php

class StyleguideColorDescriptorItemModel extends StyleguideColorItemModel
{
	public function getData()
	{
		if ($this->foreignID)
		{
			$color = $this->getColorAndVariants();
		
			$colorDescriptors = [];
			$colorDescriptors []= $color->name;
			
			$rows = $this->db->select('select description from sg_color_descriptor where itemID = ? order by position', 'i', [&$this->foreignID]);
			foreach ($rows as $row)
			{
				$colorDescriptors []= $row['description'];
			}
			
			return new ColorDescriptorItem($color, $colorDescriptors);
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
}

?>