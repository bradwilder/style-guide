<?php

class StyleguideElementsItemModel extends StyleguideItemModel
{
	public function getData()
	{
		if ($this->itemID)
		{
			$elements = [];
			$query = 'select u1.filePath as u1_file, u1.parentID as u1_path_id, ui1.shortName as u1_short_name, ui1.fullName as u1_full_name, u2.filePath as u2_file, u2.parentID as u2_path_id, ui2.shortName as u2_short_name, ui2.fullName as u2_full_name, u3.filePath as u3_file, u3.parentID as u3_path_id, ui3.shortName as u3_short_name, ui3.fullName as u3_full_name, u4.filePath as u4_file, u4.parentID as u4_path_id, ui4.shortName as u4_short_name, ui4.fullName as u4_full_name, u5.filePath as u5_file, u5.parentID as u5_path_id, ui5.shortName as u5_short_name, ui5.fullName as u5_full_name, u6.filePath as u6_file, u6.parentID as u6_path_id, ui6.shortName as u6_short_name, ui6.fullName as u6_full_name from sg_element e join sg_upload u1 on u1.id = e.upload1ID join sg_upload_file ui1 on ui1.baseID = u1.id left join sg_upload u2 on u2.id = e.upload2ID left join sg_upload_file ui2 on ui2.baseID = u2.id left join sg_upload u3 on u3.id = e.upload3ID left join sg_upload_file ui3 on ui3.baseID = u3.id left join sg_upload u4 on u4.id = e.upload4ID left join sg_upload_file ui4 on ui4.baseID = u4.id left join sg_upload u5 on u5.id = e.upload5ID left join sg_upload_file ui5 on ui5.baseID = u5.id left join sg_upload u6 on u6.id = e.upload6ID left join sg_upload_file ui6 on ui6.baseID = u6.id where e.baseID = ?';
			$elementRow = $this->db->select($query, 'i', [&$this->itemID])[0];
			for ($i = 1; $i <= 6; $i++)
			{
				$file = $elementRow['u' . $i .  '_file'];
				if ($file)
				{
					$shortName = $elementRow['u' . $i .  '_short_name'];
					$fullName = $elementRow['u' . $i .  '_full_name'];
					
					$parentPathID = $elementRow['u' . $i .  '_path_id'];
					$path = Upload::getUploadPath($parentPathID);
					
					$element = new ElementItem($path, $file, $shortName, $fullName);
					$elements []= $element;
				}
				else
				{
					break;
				}
			}
			
			return $elements;
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
	
	public function getConfigData()
	{
		if ($this->itemID)
		{
			$element = new StyleguideElementItem($this->db, $this->itemID);
			$element->read();
			$element->readItemData();
			
			$elementItem = new StyleguideConfigDetailElementItem();
			
			foreach ($element->uploads as $upload)
			{
				$elementUpload = new StyleguideConfigDetailElementUpload($upload->id, $upload->filePath, Upload::getUploadPath($upload->parentID));
				$elementItem->addImage($elementUpload);
			}
			
			return $elementItem;
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
}

?>