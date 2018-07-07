<?php

class TableView extends View_base
{
	public function __construct(TableModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/Table.template.php'));
	}
	
	public function output()
	{
		$this->template->sortableOptions = $this->model->sortableOptions;
		$this->template->striped = $this->model->striped;
		$this->template->selectable = $this->model->selectable;
		$this->template->attributes = $this->model->attributes;
		$this->template->columns = $this->model->columns;
		$this->template->rowTemplate = $this->model->rowTemplate;
		$this->template->data = $this->model->data;
		
		return parent::output();
	}
}

?>