<?php

class CredentialPageController extends PageController
{
	public function index($pageTitle)
	{
		$this->setPageData($pageTitle, 'login', false, true);
	}
}

?>