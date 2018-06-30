<?php

class PageView extends View_base
{
	public function __construct(PageModel $model, $currentUser, string $templateFile)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . "/php/View/Template/$templateFile"));
	}
	
	public function output()
	{
		$this->template->pageTitle = $this->model->pageTitle;
		$this->template->pageCode = $this->model->pageCode;
		$this->template->useMenu = $this->model->useMenu;
		$this->template->useTOC = $this->model->useTOC;
		$this->template->fullHeight = $this->model->fullHeight;
		
		return parent::output();
	}
}

?>