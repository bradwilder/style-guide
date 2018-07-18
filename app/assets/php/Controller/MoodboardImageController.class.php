<?php

class MoodboardImageController extends Controller_base
{
	public function __construct(MoodboardImageModel $model)
	{
		parent::__construct($model);
	}
	
	public function nameExists()
	{
		echo $this->model->nameExists($_POST['newValue']);
	}
	
	public function add()
	{
		echo $this->model->uploadImage($_POST['name'], $_FILES['file']['tmp_name'], $_POST['desc']);
	}
	
	public function delete()
	{
		$this->model->deleteImage($_POST['image_id']);
	}
	
	public function replace()
	{
		echo $this->model->replaceImage($_POST['image_id'], $_FILES['file']['tmp_name']);
	}
}

?>