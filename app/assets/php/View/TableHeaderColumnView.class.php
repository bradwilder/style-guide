<?php

class TableHeaderColumnView extends View_base
{
	public function __construct(TableHeaderColumnModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/TableHeaderColumn.template.php'));
	}
	
	public function output()
	{
		$this->template->tableSortable = $this->model->tableSortable;
		$this->template->columnSortable = $this->model->columnSortable;
		$this->template->attributes = $this->model->attributes;
		$this->template->cog = $this->model->cog;
		$this->template->data = $this->model->data;
		
		return parent::output();
	}
}

?>