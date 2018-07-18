<?php

class MoodboardSectionModel extends Model_base
{
	public $sectionID;
	
	public function nameExists(string $name, int $selfID = null)
	{
		return MoodboardSection::nameExists($this->db, $name, $selfID);
	}
	
	public function newSection(string $name, string $description, int $modeID)
	{
		$moodboardSection = new MoodboardSection($this->db);
		$moodboardSection->name = $name;
		$moodboardSection->description = $description;
		$moodboardSection->modeID = $modeID;
		$moodboardSection->write();
	}
	
	public function updateSection(int $sectionID, string $name, string $description)
	{
		$moodboardSection = new MoodboardSection($this->db, $sectionID);
		$moodboardSection->name = $name;
		$moodboardSection->description = $description;
		$moodboardSection->write();
	}
	
	public function deleteSection(int $sectionID)
	{
		$moodboardSection = new MoodboardSection($this->db, $sectionID);
		$moodboardSection->delete();
	}
	
	public function removeImage(int $sectionImageID)
	{
		$this->db->query('delete from mb_section_image where id = ?', 'i', [&$sectionImageID]);
	}
	
	public function addImages(int $sectionID, $imageIDs)
	{
		foreach ($imageIDs as $imageID)
		{
			$query = 'insert into mb_section_image (sectionID, imageID, position, sizeID) select ?, ?, case when max(position) is not null then max(position) + 1 else 1 end, (select id from mb_size where name = "Small") from mb_section_image where sectionID = ?';
			$this->db->query($query, 'iii', [&$sectionID, &$imageID, &$sectionID]);
		}
	}
	
	public function getAdditionalImages()
	{
		if ($this->sectionID)
		{
			$rows = $this->db->select('select i.id from mb_image i where i.id not in (select si.imageID from mb_section_image si where si.sectionID = ?) order by i.name', 'i', [&$this->sectionID]);
			
			$images = [];
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