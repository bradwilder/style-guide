<?php

class PageSectionView extends View_base
{
	public function __construct(PageSectionModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/PageSection.template.php'));
	}
	
	public function output()
	{
		$this->template->title = $this->model->title;
		$this->template->description = $this->model->description;
		$this->template->content = $this->model->content;
		$this->template->subsections = $this->model->subsections;
		
		return parent::output();
	}
}

?>