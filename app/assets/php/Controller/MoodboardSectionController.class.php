<?php

class MoodboardSectionController extends Controller_base
{
	public function __construct(MoodboardSectionModel $model)
	{
		parent::__construct($model);
	}
	
	public function nameExists()
	{
		echo MoodboardSection::nameExists($_POST['newValue'], $_POST['self_id']);
	}
	
	public function add()
	{
		$this->model->name = $_POST['name'];
		$this->model->description = $_POST['desc'];
		$this->model->modeID = $_POST['mode'];
		
		$this->model->newSection();
	}
	
	public function edit()
	{
		$this->model->sectionID = $_POST['section_id'];
		$this->model->name = $_POST['name'];
		$this->model->description = $_POST['desc'];
		
		$this->model->updateSection();
	}
	
	public function delete()
	{
		$this->model->sectionID = $_POST['section_id'];
		
		$this->model->deleteSection();
	}
	
	public function modes()
	{
		echo json_encode($this->model->getModes());
	}
	
	public function removeImage()
	{
		$this->model->sectionImageID = $_POST['section_image_id'];
		
		$this->model->removeImage();
	}
	
	public function addImages()
	{
		$this->model->sectionID = $_POST['section_id'];
		$this->model->imageIDs = [];
		foreach ($_POST as $key=>$value)
		{
			if (strstr($key, 'add-images-check-'))
			{
				$imageID = str_replace('add-images-check-', '', $key);
				$this->model->imageIDs []= $imageID;
			}
		}
		
		$this->model->addImages();
	}
	
	public function additionalImages()
	{
		$this->model->sectionID = $_GET['section_id'];
	}
}

?>