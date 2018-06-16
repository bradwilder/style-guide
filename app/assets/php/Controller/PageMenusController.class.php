<?php

class PageMenusController extends Controller_base
{
	public function __construct(PageMenusModel $model)
	{
		parent::__construct($model);
	}
	
	public function login()
	{
		$this->model->pageCode = __FUNCTION__;
		$this->model->setBrandName();
	}
	
	public function notFound()
	{
		$this->model->pageCode = __FUNCTION__;
		$this->model->setBrandName();
	}
	
	public function admin()
	{
		$this->model->pageCode = __FUNCTION__;
		$this->model->setBrandName();
	}
	
	public function moodboard()
	{
		$this->model->pageCode = __FUNCTION__;
		$this->model->setBrandName();
	}
	
	public function styleguide()
	{
		$this->model->pageCode = __FUNCTION__;
		$this->model->setBrandName();
	}
	
	public function styleguideConfig()
	{
		$this->model->pageCode = __FUNCTION__;
		$this->model->setBrandName();
	}
}

?>