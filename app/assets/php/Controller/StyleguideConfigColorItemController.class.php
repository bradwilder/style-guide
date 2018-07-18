<?php

class StyleguideConfigColorItemController extends Controller_base
{
	public function __construct(StyleguideConfigColorItemModel $model)
	{
		parent::__construct($model);
	}
	
	public function editColor()
	{
		$this->model->editColor($_POST['item_id'], $_POST['color_id'], isset($_POST['use_variants']) ? 1 : 0);
	}

	public function addColor()
	{
		$this->model->addColor($_POST['item_id'], $_POST['color_id']);
	}

	public function deleteColor()
	{
		$this->model->deleteColor($_POST['item_id'], $_POST['color_id']);
	}
	
	public function getDescriptor()
	{
		echo json_encode($this->model->getDescriptor($_GET['descriptor_id']));
	}
	
	public function editDescriptor()
	{
		$this->model->editDescriptor($_POST['descriptor_id'], $_POST['descriptor']);
	}
	
	public function deleteDescriptor()
	{
		$this->model->deleteDescriptor($_POST['descriptor_id']);
	}
	
	public function deleteDescriptors()
	{
		echo json_encode($this->model->deleteDescriptors($_POST['item_id']));
	}
	
	public function addDescriptor()
	{
		$this->model->addDescriptor($_POST['item_id'], $_POST['descriptor']);
	}
}

?>