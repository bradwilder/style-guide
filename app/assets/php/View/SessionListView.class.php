<?php

class SessionListView extends UserDataListView
{
	public function __construct(UserDataListModel $model)
	{
		parent::__construct($model);
		$this->template = new Template(__ASSETS_PATH . '/php/View/Template/SessionListView--table.template.php');
	}
}

?>