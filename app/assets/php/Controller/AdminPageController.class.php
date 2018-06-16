<?php

class AdminPageController extends PageController
{
	public function index()
	{
		$this->setPageData('Admin', 'admin', true);
	}
}

?>