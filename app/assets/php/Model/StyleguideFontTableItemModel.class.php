<?php

class StyleguideFontTableItemModel extends StyleguideItemModel
{
	public function getData()
	{
		$listings = [];
		$query = 'select l.id, l.text, f.name from sg_font_listing_table t join sg_font_listing l on l.itemID = t.baseID join sg_font f on f.id = l.fontID where t.baseID = ? order by l.position';
		$listingRows = $this->db->select($query, 'i', [&$this->foreignItem->itemID]);
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
}

?>