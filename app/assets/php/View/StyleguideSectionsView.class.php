<?php

class StyleguideSectionsView extends View_base
{
	public function __construct(StyleguideSectionsModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser);
	}
	
	public function output()
	{
		$sections = $this->model->getSections();
		
		$output = '';
		foreach ($sections as $section)
		{
			$subsections = [];
			foreach ($section->subsections as $subsectionRow)
			{
				$subsection = new PageSubsection($subsectionRow->name, $subsectionRow->description);
				$subsection->content = $this->getItemContent($subsectionRow);
				
				foreach ($subsectionRow->subSubsections as $subSubsectionRow)
				{
					$subSubsection = new PageSubsection($subSubsectionRow->name, $subSubsectionRow->description);
					$subSubsection->content = $this->getItemContent($subSubsectionRow);
					
					$subsection->subSubsections []= $subSubsection;
				}
				
				$subsections []= $subsection;
			}
			
			$pageSectionModel = new PageSectionModel();
			
			$pageSectionModel->title = $section->name;
			$pageSectionModel->subsections = $subsections;
			
			$pageSectionView = new PageSectionView($pageSectionModel, $this->currentUser);
			$output .= $pageSectionView->output();
		}
		
		return $output;
	}
	
	private function getItemContent($subsection)
	{
		$content = '';
		
		foreach ($subsection->items as $item)
		{
			$model = StyleguideItemFactory::modelByCode($item->type->code);
			$model->itemID = $item->id;
			
			$view = StyleguideItemFactory::viewByCode($item->type->code, $model, $this->currentUser);
			$itemContent = $view->output();		
			
			$content .= $itemContent;
		}
		
		return $content;
	}
}

?>