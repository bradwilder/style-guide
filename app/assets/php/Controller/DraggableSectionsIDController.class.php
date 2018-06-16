<?php

class DraggableSectionsIDController extends DraggableSectionsController
{
	public function arrange()
	{
		$this->model->id = $_POST['id'];
		
		parent::arrange();
	}
}

?>