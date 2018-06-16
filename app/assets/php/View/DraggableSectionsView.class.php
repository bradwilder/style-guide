<?php

class DraggableSectionsView extends View_base
{
	public function __construct(DraggablesModel $model)
	{
		parent::__construct($model, new Template(__ASSETS_PATH . '/php/View/Template/DraggableSections.template.php'));
	}
	
	public function output()
	{
		$this->template->sections = $this->model->getSections();
		
		return parent::output();
	}
}

?>