<?php

class UserListView extends View_base
{
	public function __construct(UserListModel $model)
	{
		parent::__construct($model, new Template(__ASSETS_PATH . '/php/View/Template/UserListView--table.template.php'));
	}
	
	public function output()
	{
		$this->template->userListItems = $this->model->getUsers();
		
		return parent::output();
	}
}

?>