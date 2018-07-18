<?php

class StyleguideConfigIconTableItemModel extends Model_base
{
	public function editFont(int $itemID, int $fontID)
	{
		$iconTableItem = new StyleguideIconTableItem($this->db, $itemID);
		$iconTableItem->fontID = $fontID;
		$iconTableItem->write();
	}
	
	public function addListing(int $itemID, string $listing)
	{
		$listing = new StyleguideIconTableListing($this->db, null, $itemID);
		$listing->html = $listing;
		$listing->write();
	}
	
	public function deleteListing(int $listingID)
	{
		$listing = new StyleguideIconTableListing($this->db, $listingID);
		$listing->delete();
	}
	
	public function getListing(int $listingID)
	{
		$listing = new StyleguideIconTableListing($this->db, $listingID);
		$listing->read();
		return $listing->html;
	}
	
	public function editListing(int $listingID, string $listing)
	{
		$listing = new StyleguideIconTableListing($this->db, $listingID);
		$listing->html = $listing;
		$listing->write();
	}
}

?>