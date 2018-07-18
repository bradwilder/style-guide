<?php

class StyleguideConfigSubsectionController extends Controller_base
{
	public function __construct(StyleguideConfigSubsectionModel $model)
	{
		parent::__construct($model);
	}
	
	public function delete()
	{
		$this->model->deleteSubsection($_POST['subsection_id']);
	}
	
	public function nameExists()
	{
		echo $this->model->nameExists($_POST['newValue'], $_POST['self_id'], $_POST['parent_id'], $_POST['parent_sub_id']);
	}
	
	public function add()
	{
		$this->model->addSubsection($_POST['section_id'], $_POST['name'], $_POST['desc'], isset($_POST['enabled']) ? 1 : 0, $_POST['section_id'], $_POST['parent_subsection_id']);
	}
	
	public function get()
	{
		echo json_encode($this->model->getSubsection($_GET['subsection_id']));
	}
	
	public function edit()
	{
		$this->model->editSubsection($_POST['subsection_id'], $_POST['name'], $_POST['desc']);
	}
	
	public function enable()
	{
		$this->model->enableSubsection($_POST['subsection_id'], $_POST['enabled']);
	}
}

?>