<?php

class PageSectionHeaderView extends View_base
{
	public function __construct(PageSectionHeaderModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/PageSectionHeader.template.php'));
	}
	
	public function output()
	{
		$this->template->title = $this->model->title;
		$this->template->editableOptions = $this->model->editableOptions;
		$this->template->buttons = $this->model->buttons;
		
		return parent::output();
	}
}

?>