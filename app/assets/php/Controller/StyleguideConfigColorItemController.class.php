<?php

class StyleguideConfigColorItemController extends Controller_base
{
	public function __construct(StyleguideConfigColorItemModel $model)
	{
        parent::__construct($model);
    }
	
	public function editColor()
	{
		$this->model->itemID = $_POST['item_id'];
		$this->model->colorID = $_POST['color_id'];
		$this->model->useVariants = isset($_POST['use_variants']) ? 1 : 0;
		
		$this->model->editColor();
	}

	public function addColor()
	{
		$this->model->itemID = $_POST['item_id'];
		$this->model->colorID = $_POST['color_id'];
		
		$this->model->addColor();
	}

	public function deleteColor()
	{
		$this->model->itemID = $_POST['item_id'];
		$this->model->colorID = $_POST['color_id'];
		
		$this->model->deleteColor();
	}
	
	public function getDescriptor()
	{
		$this->model->descriptorID = $_GET['descriptor_id'];
		
		echo json_encode($this->model->getDescriptor());
	}
	
	public function editDescriptor()
	{
		$this->model->descriptorID = $_POST['descriptor_id'];
		$this->model->descriptor = $_POST['descriptor'];
		
		$this->model->editDescriptor();
	}
	
	public function deleteDescriptor()
	{
		$this->model->descriptorID = $_POST['descriptor_id'];
		
		$this->model->deleteDescriptor();
	}
	
	public function deleteDescriptors()
	{
		$this->model->itemID = $_POST['item_id'];
		
		echo json_encode($this->model->deleteDescriptors());
	}
	
	public function addDescriptor()
	{
		$this->model->itemID = $_POST['item_id'];
		$this->model->descriptor = $_POST['descriptor'];
		
		$this->model->addDescriptor();
	}
}

?>