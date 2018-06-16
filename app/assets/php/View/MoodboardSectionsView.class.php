<?php

class MoodboardSectionsView extends View_base
{
	public function __construct(MoodboardSectionsModel $model)
	{
		parent::__construct($model, new Template(__ASSETS_PATH . '/php/View/Template/MoodboardSections.template.php'));
	}
	
	public function output()
	{
		$this->template->sections = $this->model->getSections();
		
		return parent::output();
	}
}

?>