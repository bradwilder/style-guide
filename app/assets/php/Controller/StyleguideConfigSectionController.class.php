<?php

class StyleguideConfigSectionController extends Controller_base
{
	public function __construct(StyleguideConfigSectionModel $model)
	{
		parent::__construct($model);
	}
	
	public function delete()
	{
		$this->model->delete($_POST['section_id']);
	}
	
	public function nameExists()
	{
		echo $this->model->nameExists($_POST['newValue'], $_POST['self_id']);
	}
	
	public function add()
	{
		$this->model->addSection($_POST['name'], isset($_POST['enabled']) ? 1 : 0);
	}
	
	public function get()
	{
		echo json_encode($this->model->getSection($_GET['section_id']));
	}
	
	public function edit()
	{
		$this->model->editSection($_POST['section_id'], $_POST['name']);
	}
	
	public function enable()
	{
		$this->model->enableSection($_POST['section_id'], $_POST['enabled']);
	}
}

?>