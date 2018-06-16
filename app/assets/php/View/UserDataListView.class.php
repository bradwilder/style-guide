<?php

abstract class UserDataListView extends View_base
{
	public function __construct(UserDataListModel $model)
	{
		parent::__construct($model);
	}
	
	public function output()
	{
		$this->template->data = $this->model->getData();
		
		return parent::output();
	}
}

?>