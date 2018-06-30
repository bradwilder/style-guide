<?php

class StyleguidePageView extends PageView
{
	public function __construct(StyleguidePageModel $model, $currentUser, string $templateFile)
	{
		parent::__construct($model, $currentUser, $templateFile);
	}
	
	public function output()
	{
		$this->template->fontUrls = $this->model->getWebFontURLs();
		$this->template->fontFiles = $this->model->getCSSFontFiles();
		
		return parent::output();
	}
}

?>