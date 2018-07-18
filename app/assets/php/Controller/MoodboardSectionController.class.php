<?php

class MoodboardSectionController extends Controller_base
{
	public function __construct(MoodboardSectionModel $model)
	{
		parent::__construct($model);
	}
	
	public function nameExists()
	{
		echo $this->model->nameExists($_POST['newValue'], $_POST['self_id']);
	}
	
	public function add()
	{
		$this->model->newSection($_POST['name'], $_POST['desc'], $_POST['mode']);
	}
	
	public function edit()
	{
		$this->model->updateSection($_POST['section_id'], $_POST['name'], $_POST['desc']);
	}
	
	public function delete()
	{
		$this->model->deleteSection($_POST['section_id']);
	}
	
	public function modes()
	{
		echo json_encode($this->model->getModes());
	}
	
	public function removeImage()
	{
		$this->model->removeImage($_POST['section_image_id']);
	}
	
	public function addImages()
	{
		$imageIDs = [];
		foreach ($_POST as $key=>$value)
		{
			if (strstr($key, 'add-images-check-'))
			{
				$imageIDs []= str_replace('add-images-check-', '', $key);
			}
		}
		
		$this->model->addImages($_POST['section_id'], $imageIDs);
	}
	
	public function additionalImages()
	{
		$this->model->sectionID = $_GET['section_id'];
	}
}

?>