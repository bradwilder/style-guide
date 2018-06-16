<?php

class MoodboardImageView extends View_base
{
	public function __construct(MoodboardImageModel $model)
	{
		parent::__construct($model, new Template(__ASSETS_PATH . '/php/View/Template/MoodboardImages.template.php'));
	}
	
	public function output()
	{
		$this->template->images = $this->model->getImages();
		
		return parent::output();
	}
}

?>