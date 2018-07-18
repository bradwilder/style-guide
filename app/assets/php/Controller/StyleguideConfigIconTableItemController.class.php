<?php

class StyleguideConfigIconTableItemController extends Controller_base
{
	public function __construct(StyleguideConfigIconTableItemModel $model)
	{
		parent::__construct($model);
	}
	
	public function editFont()
	{
		$this->model->editFont($_POST['item_id'], $_POST['font_id']);
	}
	
	public function addListing()
	{
		$this->model->addListing($_POST['item_id'], $_POST['listing']);
	}
	
	public function delete()
	{
		$this->model->deleteListing($_POST['listing_id']);
	}
	
	public function getListing()
	{
		echo $this->model->getListing($_GET['listing_id']);
	}
	
	public function editListing()
	{
		$this->model->editListing($_POST['listing_id'], $_POST['listing']);
	}
}

?>