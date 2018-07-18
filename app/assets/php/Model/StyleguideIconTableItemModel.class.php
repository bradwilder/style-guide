<?php

class StyleguideIconTableItemModel extends StyleguideItemModel
{
	public function getData()
	{
		if ($this->itemID)
		{
			$icons = [];
			$iconRows = $this->db->select('select l.html from sg_icon_listing_table t join sg_icon_listing l on l.itemID = t.baseID where t.baseID = ? order by l.position', 'i', [&$this->itemID]);
			foreach ($iconRows as $iconRow)
			{
				$html = $iconRow['html'];
				
				$icons []= $html;
			}
			
			return $icons;
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
	
	public function getConfigData()
	{
		if ($this->itemID)
		{
			$row = $this->db->select('select f.id, f.name from sg_icon_listing_table t join sg_font f on f.id = t.fontID where t.baseID = ?', 'i', [&$this->itemID])[0];
		
			$iconSet = new StyleguideConfigDetailIconSet($row['id'], $row['name']);
			
			$iconTable = new StyleguideIconTableItem($this->db, $this->itemID);
			$iconTable->read();
			$iconTable->readItemData();
			
			$iconTableItem = new StyleguideConfigDetailIconTableItem($iconSet);
			foreach ($iconTable->listings as $listing)
			{			
				$iconTableItem->addListing($listing);
			}
			
			return $iconTableItem;
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
}

?>