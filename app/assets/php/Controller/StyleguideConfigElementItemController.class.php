<?php

class StyleguideConfigElementItemController extends Controller_base
{
	public function __construct(StyleguideConfigElementItemModel $model)
	{
		parent::__construct($model);
	}
	
	public function deleteUpload()
	{
		$this->model->itemID = $_POST['item_id'];
		$this->model->uploadID = $_POST['upload_id'];
		
		$this->model->removeUpload();
	}
	
	public function addUpload()
	{
		$this->model->itemID = $_POST['item_id'];
		$this->model->uploadID = $_POST['upload_id'];
		
		$this->model->addUpload();
	}
}

?>