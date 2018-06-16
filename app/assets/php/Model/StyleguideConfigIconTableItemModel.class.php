<?php

class StyleguideConfigIconTableItemModel extends Model_base
{
	public $itemID;
	public $fontID;
	public $listingID;
	public $listing;
	
	public function editFont()
	{
		if ($this->itemID && $this->fontID)
		{
			$iconTableItem = new StyleguideIconTableItem($this->db, $this->itemID);
			$iconTableItem->fontID = $this->fontID;
			$iconTableItem->write();
		}
		else
		{
			throw new Exception('Item ID and font ID must be set');
		}
	}
	
	public function addListing()
	{
		if ($this->itemID && $this->listing)
		{
			$listing = new StyleguideIconTableListing($this->db);
			$listing->html = $this->listing;
			$listing->itemID = $this->itemID;
			$listing->writePosition();
		}
		else
		{
			throw new Exception('Item ID and listing must be set');
		}
	}
	
	public function deleteListing()
	{
		if ($this->itemID && $this->listingID)
		{
			$listing = new StyleguideIconTableListing($this->db, $this->listingID);
			$listing->delete();
		}
		else
		{
			throw new Exception('Item ID and listing ID must be set');
		}
	}
	
	public function getListing()
	{
		if ($this->listingID)
		{
			$listing = new StyleguideIconTableListing($this->db, $this->listingID);
			$listing->read();
			return $listing->html;
		}
		else
		{
			throw new Exception('Listing ID must be set');
		}
	}
	
	public function editListing()
	{
		if ($this->listingID && $this->listing)
		{
			$listing = new StyleguideIconTableListing($this->db, $this->listingID);
			$listing->html = $this->listing;
			$listing->write();
		}
		else
		{
			throw new Exception('Listing ID and listing must be set');
		}
	}
}

?>