<?php

class StyleguideConfigDetailController extends Controller_base
{
	public function __construct(StyleguideConfigDetailModel $model)
	{
		parent::__construct($model);
	}
	
	public function index()
	{
		$this->model->configDetailType = $_GET['configType'];
		$this->model->configDetailID = $_GET['configID'];
		
		echo json_encode($this->model->getDetailData());
	}
}

?>