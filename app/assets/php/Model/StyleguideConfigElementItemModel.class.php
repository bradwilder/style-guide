<?php

class StyleguideConfigElementItemModel extends Model_base
{
	public function removeUpload(int $itemID, int $uploadID)
	{
		$elementItem = new StyleguideElementItem($this->db, $itemID);
		$elementItem->read();
		
		$uploadIDs = [];
		
		if ($elementItem->upload1ID)
		{
			$uploadIDs []= $elementItem->upload1ID;
		}
		
		if ($elementItem->upload2ID)
		{
			$uploadIDs []= $elementItem->upload2ID;
		}
		
		if ($elementItem->upload3ID)
		{
			$uploadIDs []= $elementItem->upload3ID;
		}
		
		if ($elementItem->upload4ID)
		{
			$uploadIDs []= $elementItem->upload4ID;
		}
		
		if ($elementItem->upload5ID)
		{
			$uploadIDs []= $elementItem->upload5ID;
		}
		
		if ($elementItem->upload6ID)
		{
			$uploadIDs []= $elementItem->upload6ID;
		}
		
		$index = array_search($uploadID, $uploadIDs);
		if ($index !== false)
		{
			array_splice($uploadIDs, $index, 1);
			
			if ($uploadIDs[0])
			{
				$elementItem->upload1ID = $uploadIDs[0];
			}
			else
			{
				$elementItem->upload1ID = 0;
			}
			
			if ($uploadIDs[1])
			{
				$elementItem->upload2ID = $uploadIDs[1];
			}
			else
			{
				$elementItem->upload2ID = 0;
			}
			
			if ($uploadIDs[2])
			{
				$elementItem->upload3ID = $uploadIDs[2];
			}
			else
			{
				$elementItem->upload3ID = 0;
			}
			
			if ($uploadIDs[3])
			{
				$elementItem->upload4ID = $uploadIDs[3];
			}
			else
			{
				$elementItem->upload4ID = 0;
			}
			
			if ($uploadIDs[4])
			{
				$elementItem->upload5ID = $uploadIDs[4];
			}
			else
			{
				$elementItem->upload5ID = 0;
			}
			
			if ($uploadIDs[5])
			{
				$elementItem->upload6ID = $uploadIDs[5];
			}
			else
			{
				$elementItem->upload6ID = 0;
			}
			
			$elementItem->write();
		}
	}
	
	public function addUpload(int $itemID, int $uploadID)
	{
		$elementItem = new StyleguideElementItem($this->db, $itemID);
		$elementItem->read();
		
		if (!$elementItem->upload1ID)
		{
			$elementItem->upload1ID = $uploadID;
		}
		else if (!$elementItem->upload2ID)
		{
			$elementItem->upload2ID = $uploadID;
		}
		else if (!$elementItem->upload3ID)
		{
			$elementItem->upload3ID = $uploadID;
		}
		else if (!$elementItem->upload4ID)
		{
			$elementItem->upload4ID = $uploadID;
		}
		else if (!$elementItem->upload5ID)
		{
			$elementItem->upload5ID = $uploadID;
		}
		else
		{
			$elementItem->upload6ID = $uploadID;
		}
		
		$elementItem->write();
	}
}

?>