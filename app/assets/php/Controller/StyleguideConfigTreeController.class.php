<?php

class StyleguideConfigTreeController extends Controller_base
{
	public function __construct(StyleguideConfigTreeModel $model)
	{
		parent::__construct($model);
	}
	
	public function tree()
	{
		echo json_encode($this->model->getTree());
	}
}

?>