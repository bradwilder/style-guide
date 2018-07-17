<?php

class StyleguideIconTableItemModel extends StyleguideItemModel
{
	public function getData()
	{
		$icons = [];
		$iconRows = $this->db->select('select l.html from sg_icon_listing_table t join sg_icon_listing l on l.itemID = t.baseID where t.baseID = ? order by l.position', 'i', [&$this->foreignItem->itemID]);
		foreach ($iconRows as $iconRow)
		{
			$html = $iconRow['html'];
			
			$icons []= $html;
		}
		
		return $icons;
	}
}

?>