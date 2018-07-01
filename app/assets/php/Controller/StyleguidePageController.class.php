<?php

class StyleguidePageController extends PageController
{
	public function config($pageTitle, $pageCode, $useMenu = false, $fullHeight = false)
	{
		$this->index($pageTitle, $pageCode, $useMenu, $fullHeight);
	}
}

?>