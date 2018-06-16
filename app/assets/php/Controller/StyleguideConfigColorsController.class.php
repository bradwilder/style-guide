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
		$this->model->id = $_POST['color_id'];
		
		$this->model->setDefaultColor();
	}
	
	public function delete()
	{
		$this->model->id = $_POST['color_id'];
		
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
		$this->model->hex = $_POST['hex'];
		$var1 = $_POST['var1'];
		$var2 = $_POST['var2'];
		if ($var2 && !$var1)
		{
			$var1 = $var2;
			$var2 = null;
		}
		$this->model->variant1 = $var1;
		$this->model->variant2 = $var2;
		
		$this->model->addColor();
	}
	
	public function get()
	{
		$this->model->id = $_GET['color_id'];
		
		echo json_encode($this->model->getColor());
	}
	
	public function edit()
	{
		$this->model->id = $_POST['color_id'];
		$this->model->name = $_POST['name'];
		$this->model->hex = $_POST['hex'];
		$this->model->variant1 = $_POST['var1'];
		$this->model->variant2 = $_POST['var2'];
		
		$this->model->editColor();
	}
}

?>