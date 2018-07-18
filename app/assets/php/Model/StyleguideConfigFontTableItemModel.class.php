<?php

class StyleguideConfigFontTableItemModel extends Model_base
{
	public function editListingFont(int $listingID, int $fontID)
	{
		$listing = new StyleguideFontTableListing($this->db, $listingID);
		$listing->fontID = $fontID;
		$listing->write();
	}
	
	public function addListingCSS(int $listingID, string $css)
	{
		$listingCSS = new StyleguideFontTableListingCSS($this->db);
		$listingCSS->fontListingID = $listingID;
		$listingCSS->css = $css;
		$listingCSS->write();
	}
	
	public function deleteListingCSS(int $listingCSSID)
	{
		$listingCSS = new StyleguideFontTableListingCSS($this->db, $listingCSSID);
		$listingCSS->delete();
	}
	
	public function getListingCSS(int $listingCSSID)
	{
		$listingCSS = new StyleguideFontTableListingCSS($this->db, $listingCSSID);
		$listingCSS->read();
		return $listingCSS->css;
	}
	
	public function editListingCSS(int $listingCSSID, string $css)
	{
		$listingCSS = new StyleguideFontTableListingCSS($this->db, $listingCSSID);
		$listingCSS->css = $css;
		$listingCSS->write();
	}
	
	public function getListing(int $listingID)
	{
		$listing = new StyleguideFontTableListing($this->db, $listingID);
		$listing->read();
		return $listing->text;
	}
	
	public function editListing(int $listingID, string $listing)
	{
		$listing = new StyleguideFontTableListing($this->db, $listingID);
		$listing->text = $listing;
		$listing->write();
	}
	
	public function deleteListing(int $listingID)
	{
		$listing = new StyleguideFontTableListing($this->db, $listingID);
		$listing->delete();
	}
	
	public function addListing(int $itemID, string $listing, int $fontID)
	{
		$listing = new StyleguideFontTableListing($this->db, null, $itemID);
		$listing->text = $listing;
		$listing->fontID = $fontID;
		$listing->write();
	}
}

?>