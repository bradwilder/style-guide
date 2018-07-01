<?php

class StyleguideConfigSubsectionController extends Controller_base
{
	public function __construct(StyleguideConfigSubsectionModel $model)
	{
		parent::__construct($model);
	}
	
	public function delete()
	{
		$this->model->id = $_POST['subsection_id'];
		
		$this->model->deleteSubsection();
	}
	
	public function nameExists()
	{
		$this->model->name = $_POST['newValue'];
		$this->model->id = $_POST['self_id'];
		$this->model->sectionID = $_POST['parent_id'];
		$this->model->parentID = $_POST['parent_sub_id'];
		
		echo $this->model->nameExists();
	}
	
	public function add()
	{
		$this->model->sectionID = $_POST['section_id'];
		$this->model->parentID = $_POST['parent_subsection_id'];
		$this->model->name = $_POST['name'];
		$this->model->description = $_POST['desc'];
		$this->model->enabled = isset($_POST['enabled']) ? 1 : 0;
		
		$this->model->addSubsection();
	}
	
	public function get()
	{
		$this->model->id = $_GET['subsection_id'];
		
		echo json_encode($this->model->getSubsection());
	}
	
	public function edit()
	{
		$this->model->id = $_POST['subsection_id'];
		$this->model->name = $_POST['name'];
		$this->model->description = $_POST['desc'];
		
		$this->model->editSubsection();
	}
	
	public function enable()
	{
		$this->model->id = $_POST['subsection_id'];
		$this->model->enabled = $_POST['enabled'];
		
		$this->model->enableSubsection();
	}
}

?>