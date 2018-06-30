<?php

class MoodboardSectionImagesView extends View_base
{
	public function __construct(MoodboardSectionModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/MoodboardSectionImages.template.php'));
	}
	
	public function output()
	{
		$this->template->sectionID = $this->model->sectionID;
		$this->template->images = $this->model->getAdditionalImages();
		
		return parent::output();
	}
}

?>