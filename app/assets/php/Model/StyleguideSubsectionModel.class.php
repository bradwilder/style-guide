<?php

class StyleguideSubsectionModel extends Model_base
{
	public $subsectionID;
	
	public function getItemData()
	{
		if ($this->subsectionID)
		{
			$items = [];
			$rows = $this->db->select('select i.id, it.code from sg_item i join sg_item_type it on it.id = i.typeID where i.subsectionID = ? order by i.position', 'i', [&$this->subsectionID]);
			foreach ($rows as $row)
			{
				$foreignItem = new ForeignItem($row['id'], $row['code']);
				$items []= $foreignItem;
			}
			
			return $items;
		}
		else
		{
			throw new Exception('Subsection ID must be set');
		}
	}
}

?>