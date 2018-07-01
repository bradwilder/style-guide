<?php

class StyleguideConfigSectionController extends Controller_base
{
	public function __construct(StyleguideConfigSectionModel $model)
	{
		parent::__construct($model);
	}
	
	public function delete()
	{
		$this->model->id = $_POST['section_id'];
		
		$this->model->delete();
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
		$this->model->enabled = isset($_POST['enabled']) ? 1 : 0;
		
		$this->model->addSection();
	}
	
	public function get()
	{
		$this->model->id = $_GET['section_id'];
		
		echo json_encode($this->model->getSection());
	}
	
	public function edit()
	{
		$this->model->id = $_POST['section_id'];
		$this->model->name = $_POST['name'];
		
		$this->model->editSection();
	}
	
	public function enable()
	{
		$this->model->id = $_POST['section_id'];
		$this->model->enabled = $_POST['enabled'];
		
		$this->model->enableSection();
	}
}

?>