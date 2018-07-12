<?php

class StyleguideConfigFontTableItemModel extends Model_base
{
	public $itemID;
	public $fontID;
	public $listingID;
	public $listingCSSID;
	public $listing;
	public $css;
	
	public function editListingFont()
	{
		if ($this->listingID && $this->fontID)
		{
			$listing = new StyleguideFontTableListing($this->db, $this->listingID);
			$listing->fontID = $this->fontID;
			$listing->write();
		}
		else
		{
			throw new Exception('Item ID and font ID must be set');
		}
	}
	
	public function addListingCSS()
	{
		if ($this->listingID && $this->css)
		{
			$listingCSS = new StyleguideFontTableListingCSS($this->db);
			$listingCSS->fontListingID = $this->listingID;
			$listingCSS->css = $this->css;
			$listingCSS->write();
		}
		else
		{
			throw new Exception('Listing ID and CSS must be set');
		}
	}
	
	public function deleteListingCSS()
	{
		if ($this->listingCSSID)
		{
			$listingCSS = new StyleguideFontTableListingCSS($this->db, $this->listingCSSID);
			$listingCSS->delete();
		}
		else
		{
			throw new Exception('Listing CSS ID must be set');
		}
	}
	
	public function getListingCSS()
	{
		if ($this->listingCSSID)
		{
			$listingCSS = new StyleguideFontTableListingCSS($this->db, $this->listingCSSID);
			$listingCSS->read();
			return $listingCSS->css;
		}
		else
		{
			throw new Exception('Listing CSS ID must be set');
		}
	}
	
	public function editListingCSS()
	{
		if ($this->listingCSSID && $this->css)
		{
			$listingCSS = new StyleguideFontTableListingCSS($this->db, $this->listingCSSID);
			$listingCSS->css = $this->css;
			$listingCSS->write();
		}
		else
		{
			throw new Exception('Listing CSS ID and CSS must be set');
		}
	}
	
	public function getListing()
	{
		if ($this->listingID)
		{
			$listing = new StyleguideFontTableListing($this->db, $this->listingID);
			$listing->read();
			return $listing->text;
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
			$listing = new StyleguideFontTableListing($this->db, $this->listingID);
			$listing->text = $this->listing;
			$listing->write();
		}
		else
		{
			throw new Exception('Listing ID and listing must be set');
		}
	}
	
	public function deleteListing()
	{
		if ($this->listingID)
		{
			$listing = new StyleguideFontTableListing($this->db, $this->listingID);
			$listing->delete();
		}
		else
		{
			throw new Exception('Listing ID must be set');
		}
	}
	
	public function addListing()
	{
		if ($this->itemID && $this->listing && $this->fontID)
		{
			$listing = new StyleguideFontTableListing($this->db, null, $this->itemID);
			$listing->text = $this->listing;
			$listing->fontID = $this->fontID;
			$listing->write();
		}
		else
		{
			throw new Exception('Item ID, listing, and font ID must be set');
		}
	}
}

?>