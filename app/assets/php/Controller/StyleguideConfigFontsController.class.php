<?php

class StyleguideConfigFontsController extends Controller_base
{
	public function __construct(StyleguideConfigFontsModel $model)
	{
		parent::__construct($model);
	}
	
	public function delete()
	{
		$this->model->id = $_POST['font_id'];
		
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
		$this->model->alphabetID = $_POST['alphabet'];
		$this->model->typeCode = $_POST['type'];
		$this->model->uploadFile = $_FILES['upload'];
		$this->model->cssFile = $_POST['cssFile'];
		$this->model->importURL = $_POST['importUrl'];
		$this->model->website = $_POST['website'];
		
		$this->model->addFont();
	}
	
	public function get()
	{
		$this->model->id = $_GET['font_id'];
		
		echo json_encode($this->model->getFont());
	}
	
	public function alphabets()
	{
		echo json_encode($this->model->getAlphabets());
	}
	
	public function fontTypes()
	{
		echo json_encode($this->model->getFontTypes());
	}
	
	public function edit()
	{
		$this->model->id = $_POST['font_id'];
		$this->model->name = $_POST['name'];
		$this->model->alphabetID = $_POST['alphabet'];
		$this->model->uploadFile = $_FILES['upload'];
		$this->model->cssFile = $_POST['cssFile'];
		$this->model->importURL = $_POST['importUrl'];
		$this->model->website = $_POST['website'];
		
		$this->model->editFont();
	}
}

?>