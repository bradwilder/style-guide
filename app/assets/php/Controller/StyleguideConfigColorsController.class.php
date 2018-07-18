<?php

class StyleguideConfigColorsController extends Controller_base
{
	public function __construct(StyleguideConfigColorsModel $model)
	{
		parent::__construct($model);
	}
	
	public function getDefaultColor()
	{
		echo $this->model->getDefaultColor();
	}
	
	public function setDefaultColor()
	{
		$this->model->setDefaultColor($_POST['color_id']);
	}
	
	public function delete()
	{
		$this->model->delete($_POST['color_id']);
	}
	
	public function nameExists()
	{
		echo $this->model->nameExists($_POST['newValue'], $_POST['self_id']);
	}
	
	public function add()
	{
		$var1 = $_POST['var1'];
		$var2 = $_POST['var2'];
		if ($var2 && !$var1)
		{
			$var1 = $var2;
			$var2 = null;
		}
		
		$this->model->addColor($_POST['name'], $_POST['hex'], $var1, $var2);
	}
	
	public function get()
	{
		echo json_encode($this->model->getColor($_GET['color_id']));
	}
	
	public function edit()
	{
		$this->model->editColor($_POST['color_id'], $_POST['name'], $_POST['hex'], $_POST['var1'], $_POST['var2']);
	}
}

?>