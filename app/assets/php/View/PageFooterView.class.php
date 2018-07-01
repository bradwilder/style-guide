<?php

class PageFooterView extends View_base
{
	public function __construct(PageFooterModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser);
	}
	
	public function output()
	{
		if (!$this->template)
		{
			$this->getTemplate();
		}
		
		if ($this->template)
		{
			$this->template->pageCode = $this->model->code;
			
			return parent::output();
		}
	}
	
	private function getTemplate()
	{
		if ($this->model->code)
		{
			$this->template = new Template(__ASSETS_PATH . '/php/View/Template/Page-footer--' . $this->model->code . '.template.php');
		}
	}
}

?>