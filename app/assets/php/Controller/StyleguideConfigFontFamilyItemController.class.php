<?php

class StyleguideConfigFontFamilyItemController extends Controller_base
{
	public function __construct(StyleguideConfigFontFamilyItemModel $model)
	{
        parent::__construct($model);
    }
	
	public function edit()
	{
		$this->model->itemID = $_POST['item_id'];
		$this->model->fontID = $_POST['font_id'];
		
		$this->model->editFont();
	}
}

?>