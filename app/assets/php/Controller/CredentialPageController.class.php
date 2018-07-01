<?php

class CredentialPageController extends PageController
{
	public function index($pageTitle)
	{
		parent::index($pageTitle, 'login', false, true);
	}
}

?>