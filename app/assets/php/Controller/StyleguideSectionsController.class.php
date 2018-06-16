<?php

class StyleguideSectionsController extends Controller_base
{
	public function __construct(StyleguideSectionsModel $model)
	{
		parent::__construct($model);
	}
	
	public function index()
	{
		$this->model->enabled = true;
	}
}

?>