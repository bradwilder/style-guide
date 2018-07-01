<?php

class UserDataListController extends Controller_base
{
	public function __construct(UserDataListModel $model)
	{
		parent::__construct($model);
	}
	
	public function index()
	{
		$this->model->setUserID($_GET['user_id']);
	}
}

?>