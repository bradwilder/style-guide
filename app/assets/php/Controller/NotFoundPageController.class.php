<?php

class NotFoundPageController extends PageController
{
	public function index()
	{
		$this->setPageData('404 - Page Not Found!', 'notFound', true);
	}
}

?>