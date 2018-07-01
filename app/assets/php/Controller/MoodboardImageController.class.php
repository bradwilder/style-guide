<?php

class MoodboardImageController extends Controller_base
{
	public function __construct(MoodboardImageModel $model)
	{
		parent::__construct($model);
	}
	
	public function nameExists()
	{
		$this->model->name = $_POST['newValue'];
		
		echo $this->model->nameExists();
	}
	
	public function add()
	{
		$this->model->name = $_POST['name'];
		$this->model->description = $_POST['desc'];
		$this->model->fileName = $_FILES['file']['tmp_name'];
		
		echo $this->model->uploadImage();
	}
	
	public function delete()
	{
		$this->model->id = $_POST['image_id'];
		
		$this->model->deleteImage();
	}
	
	public function replace()
	{
		$this->model->id = $_POST['image_id'];
		$this->model->fileName = $_FILES['file']['tmp_name'];
		
		echo $this->model->replaceImage();
	}
}

?>