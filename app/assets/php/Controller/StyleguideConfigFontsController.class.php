<?php

class StyleguideConfigFontsController extends Controller_base
{
	public function __construct(StyleguideConfigFontsModel $model)
	{
		parent::__construct($model);
	}
	
	public function delete()
	{
		$this->model->delete($_POST['font_id']);
	}
	
	public function nameExists()
	{
		echo $this->model->nameExists($_POST['newValue'], $_POST['self_id']);
	}
	
	public function add()
	{
		$this->model->addFont($_POST['name'], $_POST['alphabet'], $_POST['type'], $_FILES['upload'], $_POST['cssFile'], $_POST['importUrl'], $_POST['website']);
	}
	
	public function get()
	{
		echo json_encode($this->model->getFont($_GET['font_id']));
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
		$this->model->editFont($_POST['font_id'], $_POST['name'], $_POST['alphabet'], $_FILES['upload'], $_POST['cssFile'], $_POST['importUrl'], $_POST['website']);
	}
}

?>