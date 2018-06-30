<?php

class PageMenusView extends View_base
{
	public function __construct(PageMenusModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/Page-menus.template.php'));
	}
	
	public function output()
	{
		$this->template->brandName = $this->model->brandName;
		$this->template->pageCode = $this->model->pageCode;
		
		return parent::output();
	}
}

?>