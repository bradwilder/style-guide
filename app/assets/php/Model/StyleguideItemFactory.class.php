<?php

class StyleguideItemFactory
{
	public static function readItemByID($itemID)
	{
		$db = new Db();
		
		$query = 'select it.code from sg_item i join sg_item_type it on it.id = i.typeID where i.id = ?';
		$itemTypeCode = $db->select($query, 'i', array(&$itemID))[0]['code'];
		
		$item;
		switch ($itemTypeCode)
		{
			case 'color-var':
			case 'color':
			case 'color-var-desc':
			case 'color-desc':
			case 'color-pal':
				$item = new StyleguideColorItem($db, $itemID);
				break;
			case 'font-fmy':
				$item = new StyleguideFontFamilyItem($db, $itemID);
				break;
			case 'font-tbl':
				$item = new StyleguideFontTableItem($db, $itemID);
				break;
			case 'icons-css':
				$item = new StyleguideIconTableItem($db, $itemID);
				break;
			case 'elem-seg':
				$item = new StyleguideElementItem($db, $itemID);
				break;
		}
		
		$item->read();
		$item->readExtra();
		$item->readItemData();
		return $item;
	}
	
	public static function createByCode($itemTypeCode)
	{
		$item;
		$db = new Db();
		
		switch ($itemTypeCode)
		{
			case 'color-var':
			case 'color':
			case 'color-pal':
			case 'color-var-desc':
			case 'color-desc':
				return new StyleguideColorItem($db, null, $itemTypeCode);
			case 'font-fmy':
				return new StyleguideFontFamilyItem($db);
			case 'font-tbl':
				return new StyleguideFontTableItem($db);
			case 'icons-css':
				return new StyleguideIconTableItem($db);
			case 'elem-seg':
				return new StyleguideElementItem($db);
		}
	}
}

?>