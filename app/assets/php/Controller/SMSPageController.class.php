<?php

class SMSPageController extends CredentialPageController
{
	public function __construct(SMSPageModel $model)
	{
		parent::__construct($model);
	}
	
	public function index($pageTitle)
	{
		$this->model->setSMSEnabled();
		parent::index($pageTitle);
	}
}

?>