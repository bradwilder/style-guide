<?php

class StyleguideConfigFontFamilyItemModel extends Model_base
{
	public $itemID;
	public $fontID;
	
	public function editFont()
	{
		if ($this->itemID && $this->fontID)
		{
			$fontFamilyItem = new StyleguideFontFamilyItem($this->db, $this->itemID);
			$fontFamilyItem->read();
			$fontFamilyItem->readExtra();
			
			$fontFamilyItem->fontID = $this->fontID;
			
			$fontFamilyItem->write();
		}
		else
		{
			throw new Exception('Item ID and font ID must be set');
		}
	}
}

?>