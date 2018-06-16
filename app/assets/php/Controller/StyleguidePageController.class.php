<?php

class StyleguidePageController extends PageController
{
	public function index()
	{
		$this->setPageData('Style Guide', 'styleguide', true);
	}
	
	public function config()
	{
		$this->setPageData('Style Guide Config', 'styleguideConfig', true, true);
	}
}

?>