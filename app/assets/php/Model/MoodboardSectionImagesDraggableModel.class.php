<?php

class MoodboardSectionImagesDraggableModel extends DraggablesModel
{
	public function getData()
	{
		if ($this->id)
		{		
			$section = new MoodboardSection($this->db, $this->id);
			$section->read();
			$section->readExtra();
			
			$sections = [];
			foreach ($section->images as $index=>$image)
			{
				$draggableSection = new DraggableSection($image['id'], $image['name'], true, $index + 1);
				$sections []= $draggableSection;
			}
			
			return $sections;
		}
		else
		{
			throw new Exception('Section ID must be set');
		}
	}
	
	public function arrangeSections()
	{
		if ($this->sections && $this->id)
		{		
			foreach ($this->sections as $sectionStr)
			{
				$sectionImage = MoodboardSectionImageFactory::findBySectionImage($this->id, $sectionStr[0]);
				$sectionImage->position = $sectionStr[1];
				$sectionImage->write();
			}
		}
		else
		{
			throw new Exception('Sections and section ID must be set');
		}
	}
}

?>