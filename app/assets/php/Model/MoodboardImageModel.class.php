<?php

class MoodboardImageModel extends Model_base
{
	public $id;
	public $name;
	public $description;
	public $fileName;
	
	public function nameExists()
	{
		if ($this->name)
		{
			$rows = $this->db->select('select count(*) as count from mb_image where name = ?', 's', array(&$this->name));
			$count = $rows[0]['count'];
			echo ($count != 0);
		}
		else
		{
			throw new Exception('Image name must be set');
		}
	}
	
	public function uploadImage()
	{
		if ($this->name && $this->fileName)
		{
			$uploaddir = __ASSETS_PATH . '/img/uploads/moodboard/';
			$uploadfile = $uploaddir . $this->name;
			
			if (move_uploaded_file($this->fileName, $uploadfile))
			{
				$moodboardImage = new MoodboardImage($this->db);
				$moodboardImage->name = $this->name;
				$moodboardImage->description = $this->description;
				$moodboardImage->write();
			}
			else
			{
				return "File upload failed";
			}
		}
		else
		{
			throw new Exception('Image name and file must be set');
		}
	}
	
	public function deleteImage()
	{
		if ($this->id)
		{
			$moodboardImage = new MoodBoardImage($this->id);
			$moodboardImage->read();
			
			$imageName = $moodboardImage->name;
			
			$moodboardImage->delete();
			
			$uploaddir = __ASSETS_PATH . '/img/uploads/moodboard/';
			$uploadfile = $uploaddir . $imageName;
			unlink($uploadfile);
		}
		else
		{
			throw new Exception('Image id must be set');
		}
	}
	
	public function replaceImage()
	{
		if ($this->id && $this->fileName)
		{
			$moodboardImage = new MoodBoardImage($this->id);
			$moodboardImage->read();
			
			$imageName = $moodboardImage->name;
			
			$uploaddir = __ASSETS_PATH . '/img/uploads/moodboard/';
			$uploadfile = $uploaddir . $imageName;
			unlink($uploadfile);
			
			if (move_uploaded_file($this->fileName, $uploadfile))
			{
				echo "Success";
			}
			else
			{
				echo "Failed";
			}
		}
		else
		{
			throw new Exception('Image id and file must be set');
		}
	}
	
	public function getImages()
	{
		$rows = $this->db->select('select id from mb_image order by name');
		
		$images = array();
		foreach ($rows as $row)
		{
			$image = new MoodboardImage($this->db, $row['id']);
			$image->read();
			$image->readExtra();
			
			$images []= $image;
		}
		
		return $images;
	}
}

?>