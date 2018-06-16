<?php

class StyleguideItemView extends View_base
{
	public function __construct(StyleguideItemModel $model)
	{
		parent::__construct($model);
	}
	
	public function output()
	{
		if (!$this->template)
		{
			$this->getTemplate();
		}
		
		if ($this->template)
		{
			$this->template->data = $this->model->getData();
			
			$columns = $this->model->getColumnData();
			
			$output = parent::output();
			
			return $this->wrapContentWithColumns($output, $columns);
		}
	}
	
	private function getTemplate()
	{
		switch ($this->model->foreignItem->code)
		{
			case 'color-var-desc':
			case 'color-desc':
				$this->template = new Template(__ASSETS_PATH . '/php/View/Template/ColorSwatch.template.php');
				break;
			case 'color-var':
			case 'color':
				$this->template = new Template(__ASSETS_PATH . '/php/View/Template/ColorTile.template.php');
				break;
			case 'font-fmy':
				$this->template = new Template(__ASSETS_PATH . '/php/View/Template/FontFamily.template.php');
				break;
			case 'font-tbl':
				$this->template = new Template(__ASSETS_PATH . '/php/View/Template/FontListingTable.template.php');
				break;
			case 'icons-css':
				$this->template = new Template(__ASSETS_PATH . '/php/View/Template/Icons.template.php');
				break;
			case 'elem-seg':
				$this->template = new Template(__ASSETS_PATH . '/php/View/Template/ElementListing.template.php');
				break;
		}
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