<?php

class PageOptionController extends Controller_base
{
	public function __construct(PageOptionModel $model)
	{
		parent::__construct($model);
	}
	
	public function getShowTOCList()
	{
		echo json_encode($this->model->getShowTOCOptions());
	}
	
	public function setShowTOC()
	{
		$this->model->setShowTOCOption($_POST['page_code'], $_POST['show'] ? '1' : '0');
	}
}

?>