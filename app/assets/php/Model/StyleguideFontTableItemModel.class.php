<?php

class StyleguideFontTableItemModel extends StyleguideItemModel
{
	public function getData()
	{
		if ($this->foreignID)
		{
			$listings = [];
			$query = 'select l.id, l.text, f.name from sg_font_listing_table t join sg_font_listing l on l.itemID = t.baseID join sg_font f on f.id = l.fontID where t.baseID = ? order by l.position';
			$listingRows = $this->db->select($query, 'i', [&$this->foreignID]);
			foreach ($listingRows as $listingRow)
			{
				$listingID = $listingRow['id'];
				$listingText = $listingRow['text'];
				$fontName = $listingRow['name'];
				
				$listingCSS = ['font-family: ' . $fontName . ';'];
				$listingCSSRows = $this->db->select('select c.css from sg_font_listing l join sg_font_listing_css c on c.fontListingID = l.id where l.id = ?', 'i', [&$listingID]);
				foreach ($listingCSSRows as $listingCSSRow)
				{
					$listingCSS []= $listingCSSRow['css'];
				}
				
				$fontListing = new FontListingItem($listingText, $listingCSS);
				$listings []= $fontListing;
			}
			
			return $listings;
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
	
	public function getConfigData()
	{
		if ($this->foreignID)
		{
			$fontTable = new StyleguideFontTableItem($this->db, $this->foreignID);
			$fontTable->read();
			$fontTable->readItemData();
			
			$fontTableItem = new StyleguideConfigDetailFontTableItem();
			foreach ($fontTable->listings as $listing)
			{
				$fontListingItem = new StyleguideConfigDetailFontTableListing($listing->id, $listing->text);
				foreach ($listing->cssList as $css)
				{
					$fontListingItem->addCSS($css);
				}
				
				$fontTableItem->addListing($fontListingItem);
			}
			
			return $fontTable;
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
}

?>