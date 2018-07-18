<?php

class StyleguideConfigItemModel extends Model_base
{
	public function deleteItem(int $id)
	{
		$item = StyleguideItemFactory::readItemByID($id);
		$item->delete();
	}
	
	public function nameExists(string $name, int $id)
	{
		return StyleguideItem::nameExists($this->db, $name, $id);
	}
	
	public function getItem(int $id)
	{
		$item = StyleguideItemFactory::readItemByID($id);
		return $item;
	}
	
	public function editItem(int $id, string $name)
	{
		$item = new StyleguideItem($this->db, $id);
		$item->name = $name;
		$item->write();
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
	
	public function getItemColumns(int $id)
	{
		$item = new StyleguideItem($this->db, $id);
		$item->read();
		$itemColumns = new StyleguideItemColumns($item->colXs, $item->colSm, $item->colMd, $item->colLg);
		
		$min = StyleguideItemTypeColumnMinFactory::readColMinByTypeID($item->typeID);
		$minColumns = new StyleguideItemColumns($min->minXS, $min->minSM, $min->minMD, $min->minLG);
		
		$editItem = new StyleguideItemColumnsEditItem();
		$editItem->columns = $itemColumns;
		$editItem->mins = $minColumns;
		
		return $editItem;
	}
	
	public function editItemColumns(int $id, int $lg, int $md, int $sm, int $xs)
	{
		$item = new StyleguideItem($this->db, $id);
		
		$item->colLg = $lg;
		$item->colMd = $md;
		$item->colSm = $sm;
		$item->colXs = $xs;
		
		$item->write();
	}
	
	public function addItem(string $name, string $type, int $subsectionID)
	{
		$item = StyleguideItemFactory::createByCode($type, $subsectionID);
		$item->name = $name;
		$item->write();
	}
}

?>