<?php

class StyleguideConfigItemController extends Controller_base
{
	public function __construct(StyleguideConfigItemModel $model)
	{
		parent::__construct($model);
	}
	
	public function delete()
	{
		$this->model->deleteItem($_POST['item_id']);
	}
	
	public function nameExists()
	{
		echo $this->model->nameExists($_POST['newValue'], $_POST['self_id']);
	}
	
	public function add()
	{
		$this->model->addItem($_POST['name'], $_POST['type'], $_POST['subsection_id']);
	}
	
	public function get()
	{
		echo json_encode($this->model->getItem($_GET['item_id']));
	}
	
	public function edit()
	{
		$this->model->editItem($_POST['item_id'], $_POST['name']);
	}
	
	public function getColumns()
	{
		echo json_encode($this->model->getItemColumns($_GET['item_id']));
	}
	
	public function getTypes()
	{
		echo json_encode($this->model->getItemTypes());
	}
	
	public function editColumns()
	{
		$this->model->editItemColumns($_POST['item_id'], $_POST['lg'], $_POST['md'], $_POST['sm'], $_POST['xs']);
	}
}

?>