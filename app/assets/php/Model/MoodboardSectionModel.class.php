<?php

class MoodboardSectionModel extends Model_base
{
	public $sectionID;
	public $name;
	public $description;
	public $modeID;
	public $sectionImageID;
	public $imageIDs;
	
	public function newSection()
	{
		if ($this->name)
		{
			$moodboardSection = new MoodboardSection($this->db);
			$moodboardSection->name = $this->name;
			$moodboardSection->description = $this->description;
			$moodboardSection->modeID = $this->modeID;
			$moodboardSection->write();
		}
		else
		{
			throw new Exception('Section name must be set');
		}
	}
	
	public function updateSection()
	{
		if ($this->sectionID)
		{
			$moodboardSection = new MoodboardSection($this->db, $this->sectionID);
			$moodboardSection->name = $this->name;
			$moodboardSection->description = $this->description;
			$moodboardSection->write();
		}
		else
		{
			throw new Exception('Section ID must be set');
		}
	}
	
	public function deleteSection()
	{
		if ($this->sectionID)
		{
			$moodboardSection = new MoodboardSection($this->db, $this->sectionID);
			$moodboardSection->delete();
		}
		else
		{
			throw new Exception('Section ID must be set');
		}
	}
	
	public function removeImage()
	{
		if ($this->sectionImageID)
		{
			$this->db->query('delete from mb_section_image where id = ?', 'i', array(&$this->sectionImageID));
		}
		else
		{
			throw new Exception('Section image ID must be set');
		}
	}
	
	public function addImages()
	{
		if ($this->sectionID && $this->imageIDs)
		{
			foreach ($this->imageIDs as $imageID)
			{
				$query = 'insert into mb_section_image (sectionID, imageID, position, sizeID) select ' . $this->sectionID . ', ' . $imageID . ', case when max(position) is not null then max(position) + 1 else 1 end, (select id from mb_size where name = "Small") from mb_section_image where sectionID = ?';
				$this->db->query($query, 'i', array(&$this->sectionID));
			}
		}
		else
		{
			throw new Exception('Section ID and images must be set');
		}
	}
	
	public function getAdditionalImages()
	{
		if ($this->sectionID)
		{
			$rows = $this->db->select('select i.id from mb_image i where i.id not in (select si.imageID from mb_section_image si where si.sectionID = ?) order by i.name', 'i', array(&$this->sectionID));
			
			$images = array();
			foreach ($rows as $row)
			{
				$moodboardImage = new MoodboardImage($this->db, $row['id']);
				$moodboardImage->read();
				
				$images []= $moodboardImage;
			}
			
			return $images;
		}
		else
		{
			throw new Exception('Section ID must be set');
		}
	}
	
	public function getModes()
	{
		return $this->db->select('select id, name from mb_mode order by id');
	}
}

?>