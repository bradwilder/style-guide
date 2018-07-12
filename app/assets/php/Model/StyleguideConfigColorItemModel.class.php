<?php

class StyleguideConfigColorItemModel extends Model_base
{
	public $itemID;
	public $colorID;
	public $useVariants;
	public $descriptorID;
	public $descriptor;
	
	public function editColor()
	{
		if ($this->itemID)
		{
			$colorItem = new StyleguideColorItem($this->db, $this->itemID);
			$colorItem->read();
			$colorItem->readExtra();
			if ($this->colorID)
			{
				$colorItem->color1ID = $this->colorID;
			}
			
			$currType = $colorItem->type->code;
			if ($this->useVariants)
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
		else
		{
			throw new Exception('Item ID must be set');
		}
	}
	
	public function addColor()
	{
		if ($this->itemID && $this->colorID)
		{
			$colorItem = new StyleguideColorItem($this->db, $this->itemID);
			$colorItem->read();
			
			if (!$colorItem->color1ID)
			{
				$colorItem->color1ID = $this->colorID;
			}
			else if (!$colorItem->color2ID)
			{
				$colorItem->color2ID = $this->colorID;
			}
			else if (!$colorItem->color3ID)
			{
				$colorItem->color3ID = $this->colorID;
			}
			else if (!$colorItem->color4ID)
			{
				$colorItem->color4ID = $this->colorID;
			}
			else if (!$colorItem->color5ID)
			{
				$colorItem->color5ID = $this->colorID;
			}
			else
			{
				$colorItem->color6ID = $this->colorID;
			}
			
			$colorItem->write();
		}
		else
		{
			throw new Exception('Item ID and color ID must be set');
		}
	}
	
	public function deleteColor()
	{
		if ($this->itemID && $this->colorID)
		{
			$colorItem = new StyleguideColorItem($this->db, $this->itemID);
			$colorItem->read();
			
			$colorIDs = array();
			
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
			
			$index = array_search($this->colorID, $colorIDs);
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
		else
		{
			throw new Exception('Item ID and color ID must be set');
		}
	}
	
	public function getDescriptor()
	{
		if ($this->descriptorID)
		{
			$descriptor = new StyleguideColorItemDescriptor($this->db, $this->descriptorID);
			$descriptor->read();
			return $descriptor->description;
		}
		else
		{
			throw new Exception('Descriptor ID must be set');
		}
	}
	
	public function editDescriptor()
	{
		if ($this->descriptorID && $this->descriptor)
		{
			$descriptor = new StyleguideColorItemDescriptor($this->db, $this->descriptorID);
			$descriptor->description = $this->descriptor;
			$descriptor->write();
		}
		else
		{
			throw new Exception('Descriptor ID and description must be set');
		}
	}
	
	public function deleteDescriptor()
	{
		if ($this->descriptorID)
		{
			$descriptor = new StyleguideColorItemDescriptor($this->db, $this->descriptorID);
			$descriptor->delete();
		}
		else
		{
			throw new Exception('Descriptor ID must be set');
		}
	}
	
	public function deleteDescriptors()
	{
		if ($this->itemID)
		{
			$colorItem = new StyleguideColorItem($this->db, $this->itemID);
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
		else
		{
			throw new Exception('Item ID must be set');
		}
	}
	
	public function addDescriptor()
	{
		if ($this->itemID && $this->descriptor)
		{
			$descriptor = new StyleguideColorItemDescriptor($this->db, null, $this->itemID);
			$descriptor->description = $this->descriptor;
			$descriptor->write();
		}
		else
		{
			throw new Exception('Item ID and description must be set');
		}
	}
}

?>