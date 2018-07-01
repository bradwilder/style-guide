<?php

class PageMenusController extends Controller_base
{
	public function __construct(PageMenusModel $model)
	{
		parent::__construct($model);
	}
	
	public function index($code)
	{
		$this->model->pageCode = $code;
		$this->model->setBrandName();
	}
}

?>