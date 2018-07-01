<?php

class MoodboardSectionContentController extends Controller_base
{
	public function __construct(MoodboardSectionContentModel $model)
	{
		parent::__construct($model);
	}
	
	public function index($section)
	{
		$this->model->section = $section;
	}
}

?>