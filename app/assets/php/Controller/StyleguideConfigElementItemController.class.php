<?php

class StyleguideConfigElementItemController extends Controller_base
{
	public function __construct(StyleguideConfigElementItemModel $model)
	{
		parent::__construct($model);
	}
	
	public function deleteUpload()
	{
		$this->model->removeUpload($_POST['item_id'], $_POST['upload_id']);
	}
	
	public function addUpload()
	{
		$this->model->addUpload($_POST['item_id'], $_POST['upload_id']);
	}
}

?>