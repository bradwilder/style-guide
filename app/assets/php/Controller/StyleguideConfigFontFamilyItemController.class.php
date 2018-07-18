<?php

class StyleguideConfigFontFamilyItemController extends Controller_base
{
	public function __construct(StyleguideConfigFontFamilyItemModel $model)
	{
		parent::__construct($model);
	}
	
	public function edit()
	{
		$this->model->editFont($_POST['item_id'], $_POST['font_id']);
	}
}

?>