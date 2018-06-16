<?php

class StyleguideConfigFontTableItemController extends Controller_base
{
	public function __construct(StyleguideConfigFontTableItemModel $model)
	{
        parent::__construct($model);
    }
	
	public function editListingFont()
	{
		$this->model->listingID = $_POST['listing_id'];
		$this->model->fontID = $_POST['font_id'];
		
		$this->model->editListingFont();
	}
	
	public function addListingCSS()
	{
		$this->model->listingID = $_POST['listing_id'];
		$this->model->css = $_POST['css'];
		
		$this->model->addListingCSS();
	}
	
	public function deleteListingCSS()
	{
		$this->model->listingCSSID = $_POST['listing_css_id'];
		
		$this->model->deleteListingCSS();
	}
	
	public function getListingCSS()
	{
		$this->model->listingCSSID = $_GET['listing_css_id'];
		
		echo $this->model->getListingCSS();
	}
	
	public function editListingCSS()
	{
		$this->model->listingCSSID = $_POST['listing_css_id'];
		$this->model->css = $_POST['css'];
		
		$this->model->editListingCSS();
	}
	
	public function getListing()
	{
		$this->model->listingID = $_GET['listing_id'];
		
		echo $this->model->getListing();
	}
	
	public function editListing()
	{
		$this->model->listingID = $_POST['listing_id'];
		$this->model->listing = $_POST['listing'];
		
		$this->model->editListing();
	}
	
	public function deleteListing()
	{
		$this->model->listingID = $_POST['listing_id'];
		
		$this->model->deleteListing();
	}
	
	public function addListing()
	{
		$this->model->itemID = $_POST['item_id'];
		$this->model->listing = $_POST['listing'];
		$this->model->fontID = $_POST['font_id'];
		
		$this->model->addListing();
	}
}

?>