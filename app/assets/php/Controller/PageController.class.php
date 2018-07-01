<?php

class PageController extends Controller_base
{
	public function __construct(PageModel $model)
	{
		parent::__construct($model);
	}
	
	public function index($pageTitle, $pageCode, $useMenu = false, $fullHeight = false)
	{
		$this->model->pageTitle = $pageTitle;
		$this->model->pageCode = $pageCode;
		$this->model->useMenu = $useMenu;
		$this->model->setUseTOC();
		$this->model->fullHeight = $fullHeight;
	}
}

?>