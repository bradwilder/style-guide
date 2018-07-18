<?php

class MoodboardImageModel extends Model_base
{
	private static $uploadDir = __ASSETS_PATH . '/img/uploads/moodboard/';
	
	public function nameExists(string $name)
	{
		return MoodboardImage::nameExists($this->db, $name);
	}
	
	public function uploadImage(string $name, string $fileName, string $description)
	{
		$uploadfile = self::$uploadDir . $name;
		
		if (move_uploaded_file($fileName, $uploadfile))
		{
			$moodboardImage = new MoodboardImage($this->db);
			$moodboardImage->name = $name;
			$moodboardImage->description = $description;
			$moodboardImage->write();
		}
		else
		{
			return "File upload failed";
		}
	}
	
	public function deleteImage(int $id)
	{
		$moodboardImage = new MoodBoardImage($id);
		$moodboardImage->read();
		
		$uploadfile = self::$uploadDir . $moodboardImage->name;
		
		if (unlink($uploadfile))
		{
			$moodboardImage->delete();
		}
	}
	
	public function replaceImage(int $id, string $fileName)
	{
		$moodboardImage = new MoodBoardImage($id);
		$moodboardImage->read();
		
		$uploadfile = self::$uploadDir . $moodboardImage->name;
		
		if (unlink($uploadfile) && move_uploaded_file($fileName, $uploadfile))
		{
			echo "Success";
		}
		else
		{
			echo "Failed";
		}
	}
	
	public function getData()
	{
		$rows = $this->db->select('select id from mb_image order by name');
		
		$images = [];
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