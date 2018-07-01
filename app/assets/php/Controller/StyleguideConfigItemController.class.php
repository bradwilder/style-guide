<?php

class StyleguideConfigItemController extends Controller_base
{
	public function __construct(StyleguideConfigItemModel $model)
	{
		parent::__construct($model);
	}
	
	public function delete()
	{
		$this->model->id = $_POST['item_id'];
		
		$this->model->deleteItem();
	}
	
	public function nameExists()
	{
		$this->model->name = $_POST['newValue'];
		$this->model->id = $_POST['self_id'];
		
		echo $this->model->nameExists();
	}
	
	public function add()
	{
		$this->model->name = $_POST['name'];
		$this->model->type = $_POST['type'];
		$this->model->subsectionID = $_POST['subsection_id'];
		
		$this->model->addItem();
	}
	
	public function get()
	{
		$this->model->id = $_GET['item_id'];
		
		echo json_encode($this->model->getItem());
	}
	
	public function edit()
	{
		$this->model->id = $_POST['item_id'];
		$this->model->name = $_POST['name'];
		
		$this->model->editItem();
	}
	
	public function getColumns()
	{
		$this->model->id = $_GET['item_id'];
		
		echo json_encode($this->model->getItemColumns());
	}
	
	public function getTypes()
	{
		echo json_encode($this->model->getItemTypes());
	}
	
	public function editColumns()
	{
		$this->model->id = $_POST['item_id'];
		$this->model->columns = new StyleguideItemColumns($_POST['xs'], $_POST['sm'], $_POST['md'], $_POST['lg']);
		
		$this->model->editItemColumns();
	}
}

?>