<?php

class SMSPageView extends PageView
{
	public function __construct(SMSPageModel $model, $currentUser, string $templateFile)
	{
		parent::__construct($model, $currentUser, $templateFile);
	}
	
	public function output()
	{
		$this->template->smsEnabled = $this->model->smsEnabled;
		
		return parent::output();
	}
}

?>