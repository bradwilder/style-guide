<?php

class StyleguideConfigTreeController extends Controller_base
{
	public function __construct(StyleguideConfigTreeModel $model)
	{
		parent::__construct($model);
	}
	
	public function index()
	{
		echo json_encode($this->model->getTree());
	}
}

?>