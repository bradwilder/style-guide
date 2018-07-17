<?php

class StyleguideConfigItemModel extends Model_base
{
	public $id;
	public $name;
	public $type;
	public $subsectionID;
	public $columns;
	
	public function deleteItem()
	{
		if ($this->id)
		{
			$item = StyleguideItemFactory::readItemByID($this->id);
			$item->delete();
		}
		else
		{
			throw new Exception('Item ID must be set');
		}
	}
	
	public function nameExists()
	{
		if ($this->name)
		{
			return StyleguideItem::nameExists($this->name, $this->id);
		}
		else
		{
			throw new Exception('Item name must be set');
		}
	}
	
	public function getItem()
	{
		if ($this->id)
		{
			$item = StyleguideItemFactory::readItemByID($this->id);
			return $item;
		}
		else
		{
			throw new Exception('Item ID must be set');
		}
	}
	
	public function editItem()
	{
		if ($this->id && $this->name)
		{
			$item = new StyleguideItem($this->db, $this->id);
			$item->name = $this->name;
			$item->write();
		}
		else
		{
			throw new Exception('Item ID and name must be set');
		}
	}
	
	public function getItemTypes()
	{
		$types = [];
		$rows = $this->db->select('select id from sg_item_type');
		foreach ($rows as $row)
		{
			$type = new StyleguideItemType($this->db, $row['id']);
			$type->read();
			
			$types []= $type;
		}
		
		return $types;
	}
	
	public function getItemColumns()
	{
		if ($this->id)
		{
			$item = new StyleguideItem($this->db, $this->id);
			$item->read();
			$itemColumns = new StyleguideItemColumns($item->colXs, $item->colSm, $item->colMd, $item->colLg);
			
			$min = StyleguideItemTypeColumnMinFactory::readColMinByTypeID($item->typeID);
			$minColumns = new StyleguideItemColumns($min->minXS, $min->minSM, $min->minMD, $min->minLG);
			
			$editItem = new StyleguideItemColumnsEditItem();
			$editItem->columns = $itemColumns;
			$editItem->mins = $minColumns;
			
			return $editItem;
		}
		else
		{
			throw new Exception('Item ID must be set');
		}
	}
	
	public function editItemColumns()
	{
		if ($this->id && $this->columns)
		{
			$item = new StyleguideItem($this->db, $this->id);
			
			$item->colLg = $this->columns->lg;
			$item->colMd = $this->columns->md;
			$item->colSm = $this->columns->sm;
			$item->colXs = $this->columns->xs;
			
			$item->write();
		}
		else
		{
			throw new Exception('Item ID and columns must be set');
		}
	}
	
	public function addItem()
	{
		if ($this->name && $this->type && $this->subsectionID)
		{
			$item = StyleguideItemFactory::createByCode($this->type, $this->subsectionID);
			$item->name = $this->name;
			$item->write();
		}
		else
		{
			throw new Exception('Item name, type code, and subsection ID must be set');
		}
	}
}

?>