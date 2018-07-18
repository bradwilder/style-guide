<?php

class StyleguideConfigColorItemModel extends Model_base
{
	public function editColor(int $itemID, int $colorID, bool $useVariants)
	{
		$colorItem = new StyleguideColorItem($this->db, $itemID);
		$colorItem->read();
		$colorItem->readExtra();
		if ($colorID)
		{
			$colorItem->color1ID = $colorID;
		}
		
		$currType = $colorItem->type->code;
		if ($useVariants)
		{
			if (!strpos($currType, 'var'))
			{
				$code = str_replace('color', 'color-var', $currType);
				$type = StyleguideItemTypeFactory::readTypeByCode($code);
				$colorItem->typeID = $type->id;
				
				$columnMin = StyleguideItemTypeColumnMinFactory::readColMinByTypeID($type->id);
				
				$colorItem->colLg = max($colorItem->colLg, $columnMin->minLG);
				$colorItem->colMd = max($colorItem->colMd, $columnMin->minMD);
				$colorItem->colSm = max($colorItem->colSm, $columnMin->minSM);
				$colorItem->colXs = max($colorItem->colXs, $columnMin->minXS);
			}
		}
		else
		{
			if (strpos($currType, 'var'))
			{
				$code = str_replace('-var', '', $currType);
				$type = StyleguideItemTypeFactory::readTypeByCode($code);
				$colorItem->typeID = $type->id;
			}
		}
		
		$colorItem->write();
	}
	
	public function addColor(int $itemID, int $colorID)
	{
		$colorItem = new StyleguideColorItem($this->db, $itemID);
		$colorItem->read();
		
		if (!$colorItem->color1ID)
		{
			$colorItem->color1ID = $colorID;
		}
		else if (!$colorItem->color2ID)
		{
			$colorItem->color2ID = $colorID;
		}
		else if (!$colorItem->color3ID)
		{
			$colorItem->color3ID = $colorID;
		}
		else if (!$colorItem->color4ID)
		{
			$colorItem->color4ID = $colorID;
		}
		else if (!$colorItem->color5ID)
		{
			$colorItem->color5ID = $colorID;
		}
		else
		{
			$colorItem->color6ID = $colorID;
		}
		
		$colorItem->write();
	}
	
	public function deleteColor(int $itemID, int $colorID)
	{
		$colorItem = new StyleguideColorItem($this->db, $itemID);
		$colorItem->read();
		
		$colorIDs = [];
		
		if ($colorItem->color1ID)
		{
			$colorIDs []= $colorItem->color1ID;
		}
		
		if ($colorItem->color2ID)
		{
			$colorIDs []= $colorItem->color2ID;
		}
		
		if ($colorItem->color3ID)
		{
			$colorIDs []= $colorItem->color3ID;
		}
		
		if ($colorItem->color4ID)
		{
			$colorIDs []= $colorItem->color4ID;
		}
		
		if ($colorItem->color5ID)
		{
			$colorIDs []= $colorItem->color5ID;
		}
		
		if ($colorItem->color6ID)
		{
			$colorIDs []= $colorItem->color6ID;
		}
		
		$index = array_search($colorID, $colorIDs);
		if ($index !== false)
		{
			array_splice($colorIDs, $index, 1);
			
			if ($colorIDs[0])
			{
				$colorItem->color1ID = $colorIDs[0];
			}
			else
			{
				$colorItem->color1ID = 0;
			}
			
			if ($colorIDs[1])
			{
				$colorItem->color2ID = $colorIDs[1];
			}
			else
			{
				$colorItem->color2ID = 0;
			}
			
			if ($colorIDs[2])
			{
				$colorItem->color3ID = $colorIDs[2];
			}
			else
			{
				$colorItem->color3ID = 0;
			}
			
			if ($colorIDs[3])
			{
				$colorItem->color4ID = $colorIDs[3];
			}
			else
			{
				$colorItem->color4ID = 0;
			}
			
			if ($colorIDs[4])
			{
				$colorItem->color5ID = $colorIDs[4];
			}
			else
			{
				$colorItem->color5ID = 0;
			}
			
			if ($colorIDs[5])
			{
				$colorItem->color6ID = $colorIDs[5];
			}
			else
			{
				$colorItem->color6ID = 0;
			}
			
			$colorItem->write();
		}
	}
	
	public function getDescriptor(int $descriptorID)
	{
		$descriptor = new StyleguideColorItemDescriptor($this->db, $descriptorID);
		$descriptor->read();
		return $descriptor->description;
	}
	
	public function editDescriptor(int $descriptorID, string $descriptor)
	{
		$descriptor = new StyleguideColorItemDescriptor($this->db, $descriptorID);
		$descriptor->description = $descriptor;
		$descriptor->write();
	}
	
	public function deleteDescriptor(int $descriptorID)
	{
		$descriptor = new StyleguideColorItemDescriptor($this->db, $descriptorID);
		$descriptor->delete();
	}
	
	public function deleteDescriptors(int $itemID)
	{
		$colorItem = new StyleguideColorItem($this->db, $itemID);
		$colorItem->read();
		$colorItem->readExtra();
		$colorItem->readItemData();
		
		foreach ($colorItem->descriptors as $descriptor)
		{
			$descriptor->delete();
		}
		
		$currType = $colorItem->type->code;
		$code = str_replace('-desc', '', $currType);
		$type = StyleguideItemTypeFactory::readTypeByCode($code);
		$colorItem->typeID = $type->id;
		$colorItem->write();
		
		return $type;
	}
	
	public function addDescriptor(int $itemID, string $descriptor)
	{
		$descriptor = new StyleguideColorItemDescriptor($this->db, null, $itemID);
		$descriptor->description = $descriptor;
		$descriptor->write();
	}
}

?>