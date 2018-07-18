<?php

class StyleguideConfigDetailController extends Controller_base
{
	public function __construct(StyleguideConfigDetailModel $model)
	{
		parent::__construct($model);
	}
	
	public function index()
	{
		echo json_encode($this->model->getDetailData($_GET['configType'], $_GET['configID']));
	}
}

?>