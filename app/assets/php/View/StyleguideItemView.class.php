<?php

abstract class StyleguideItemView extends View_base
{
	public function __construct(StyleguideItemModel $model, $currentUser, $templateFile)
	{
		parent::__construct($model, $currentUser, $templateFile);
	}
	
	public function output()
	{
		$this->template->data = $this->model->getData();
		
		return $this->wrapContentWithColumns(parent::output(), $this->model->getColumnData());
	}
	
	private function wrapContentWithColumns($itemContent, $columnItem)
	{
		if (!$columnItem->lg && !$columnItem->md && !$columnItem->sm && !$columnItem->xs)
		{
			$columnItem->xs = 12;
		}
		
		$lgCol = $columnItem->lg ? 'col-lg-' . $columnItem->lg : '';
		$mdCol = $columnItem->md ? 'col-md-' . $columnItem->md : '';
		$smCol = $columnItem->sm ? 'col-sm-' . $columnItem->sm : '';
		$xsCol = $columnItem->xs ? 'col-xs-' . $columnItem->xs : '';
		
		return '<div class="' . $lgCol . ' ' . $mdCol . ' ' . $smCol . ' ' . $xsCol . ' page-section--b-margin">' . $itemContent . '</div>';
	}
}

?>