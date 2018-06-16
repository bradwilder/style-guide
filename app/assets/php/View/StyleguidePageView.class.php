<?php

class StyleguidePageView extends PageView
{
	public function __construct(StyleguidePageModel $model, string $templateFile)
	{
		parent::__construct($model, $templateFile);
	}
	
	public function output()
	{
		$this->template->fontUrls = $this->model->getWebFontURLs();
		$this->template->fontFiles = $this->model->getCSSFontFiles();
		
		return parent::output();
	}
}

?>