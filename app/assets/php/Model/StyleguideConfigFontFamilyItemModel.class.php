<?php

class StyleguideConfigFontFamilyItemModel extends Model_base
{
	public function editFont(int $itemID, int $fontID)
	{
		$fontFamilyItem = new StyleguideFontFamilyItem($this->db, $itemID);
		$fontFamilyItem->read();
		$fontFamilyItem->readExtra();
		
		$fontFamilyItem->fontID = $fontID;
		
		$fontFamilyItem->write();
	}
}

?>