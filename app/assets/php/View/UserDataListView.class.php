<?php

class UserDataListView extends View_base
{
	public function __construct(UserDataListModel $model, string $templateFile)
	{
		parent::__construct($model, new Template(__ASSETS_PATH . "/php/View/Template/$templateFile"));
	}
	
	public function output()
	{
		$this->template->data = $this->model->getData();
		
		return parent::output();
	}
}

?>