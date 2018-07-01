<?php

class DraggableSectionsController extends Controller_base
{
	public function __construct(DraggablesModel $model)
	{
		parent::__construct($model);
	}
	
	public function index()
	{
		$this->model->id = $_GET['id'];
	}
	
	public function arrange()
	{
		$this->model->sections = $this->parseSectionStr($_POST['sections']);
		
		$this->model->arrangeSections();
	}
	
	private function parseSectionStr($sectionStr)
	{
		$sectionStrs = explode(',', $sectionStr);
		$sectionArrays = array();
		foreach ($sectionStrs as $sectionStr)
		{
			$sectionArray = explode(':', $sectionStr);
			$sectionArrays []= $sectionArray;
		}
		
		return $sectionArrays;
	}
}

?>