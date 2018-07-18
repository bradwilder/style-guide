<?php

class StyleguideConfigFontTableItemController extends Controller_base
{
	public function __construct(StyleguideConfigFontTableItemModel $model)
	{
		parent::__construct($model);
	}
	
	public function editListingFont()
	{
		$this->model->editListingFont($_POST['listing_id'], $_POST['font_id']);
	}
	
	public function addListingCSS()
	{
		$this->model->addListingCSS($_POST['listing_id'], $_POST['css']);
	}
	
	public function deleteListingCSS()
	{
		$this->model->deleteListingCSS($_POST['listing_css_id']);
	}
	
	public function getListingCSS()
	{
		echo $this->model->getListingCSS($_GET['listing_css_id']);
	}
	
	public function editListingCSS()
	{
		$this->model->editListingCSS($_POST['listing_css_id'], $_POST['css']);
	}
	
	public function getListing()
	{
		echo $this->model->getListing($_GET['listing_id']);
	}
	
	public function editListing()
	{
		$this->model->editListing($_POST['listing_id'], $_POST['listing']);
	}
	
	public function deleteListing()
	{
		$this->model->deleteListing($_POST['listing_id']);
	}
	
	public function addListing()
	{
		$this->model->addListing($_POST['item_id'], $_POST['listing'], $_POST['font_id']);
	}
}

?>