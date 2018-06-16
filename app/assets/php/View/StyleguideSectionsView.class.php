<?php

class StyleguideSectionsView extends View_base
{
	public function __construct(StyleguideSectionsModel $model)
	{
		parent::__construct($model);
	}
	
	public function output()
	{
		$sections = $this->model->getSections();
		
		$output = '';
		foreach ($sections as $section)
		{
			$title = $section->name;
			
			$subsections = array();
			foreach ($section->subsections as $subsectionRow)
			{
				$subsection = new PageSubsection($subsectionRow->name, $subsectionRow->description);
				$subsection->content = $this->getForeignTableContent($subsectionRow->id);
				
				foreach ($subsectionRow->subSubsections as $subSubsectionRow)
				{
					$subSubsection = new PageSubsection($subSubsectionRow->name, $subSubsectionRow->description);
					$subSubsection->content = $this->getForeignTableContent($subSubsectionRow->id);
					
					$subsection->subSubsections []= $subSubsection;
				}
				
				$subsections []= $subsection;
			}
			
			$pageSectionModel = new PageSectionModel($this->model);
			
			$pageSectionModel->title = $section->name;
			$pageSectionModel->subsections = $subsections;
			
			$pageSectionView = new PageSectionView($pageSectionModel);
			$output .= $pageSectionView->output();
		}
		
		return $output;
	}
	
	private function getForeignTableContent($subsectionID)
	{
		$smodel = new StyleguideSubsectionModel();
		$smodel->subsectionID = $subsectionID;
		$data = $smodel->getForeignData();

		$content = '';

		foreach ($data as $row)
		{
			$imodel = new StyleguideItemModel();
			$imodel->foreignItem = $row;

			$view = new StyleguideItemView($imodel);
			$itemContent = $view->output();		

			$content .= $itemContent;
		}

		return $content;
	}
}

?>