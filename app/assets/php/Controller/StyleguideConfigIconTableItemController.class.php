<?php

class StyleguideConfigIconTableItemController extends Controller_base
{
	public function __construct(StyleguideConfigIconTableItemModel $model)
	{
		parent::__construct($model);
	}
	
	public function editFont()
	{
		$this->model->itemID = $_POST['item_id'];
		$this->model->fontID = $_POST['font_id'];
		
		$this->model->editFont();
	}
	
	public function addListing()
	{
		$this->model->itemID = $_POST['item_id'];
		$this->model->listing = $_POST['listing'];
		
		$this->model->addListing();
	}
	
	public function delete()
	{
		$this->model->itemID = $_POST['item_id'];
		$this->model->listingID = $_POST['listing_id'];
		
		$this->model->deleteListing();
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
}

?>