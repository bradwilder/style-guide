<?php

class StyleguideItemFactory
{
	public static function readItemByID($itemID)
	{
		$db = new Db();
		
		$query = 'select it.code from sg_item i join sg_item_type it on it.id = i.typeID where i.id = ?';
		$itemTypeCode = $db->select($query, 'i', [&$itemID])[0]['code'];
		
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
	
	public static function createByCode($itemTypeCode, int $subsectionID = null)
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
				return new StyleguideColorItem($db, null, $itemTypeCode, $subsectionID);
			case 'font-fmy':
				return new StyleguideFontFamilyItem($db, null, $subsectionID);
			case 'font-tbl':
				return new StyleguideFontTableItem($db, null, $subsectionID);
			case 'icons-css':
				return new StyleguideIconTableItem($db, null, $subsectionID);
			case 'elem-seg':
				return new StyleguideElementItem($db, null, $subsectionID);
		}
	}
	
	public static function modelByCode($code)
	{
		switch ($code)
		{
			case 'color-var-desc':
			case 'color-desc':
				return new StyleguideColorDescriptorItemModel();
			case 'color-var':
			case 'color':
				return new StyleguideColorItemModel();
			case 'font-fmy':
				return new StyleguideFontFamilyItemModel();
			case 'font-tbl':
				return new StyleguideFontTableItemModel();
			case 'icons-css':
				return new StyleguideIconTableItemModel();
			case 'elem-seg':
				return new StyleguideElementsItemModel();
		}
	}
	
	public static function viewByCode($code, $model, $currentUser)
	{
		$template;
		switch ($code)
		{
			case 'color-var-desc':
			case 'color-desc':
				$template = new Template(__ASSETS_PATH . '/php/View/Template/ColorSwatch.template.php');
				break;
			case 'color-var':
			case 'color':
				$template = new Template(__ASSETS_PATH . '/php/View/Template/ColorTile.template.php');
				break;
			case 'font-fmy':
				$template = new Template(__ASSETS_PATH . '/php/View/Template/FontFamily.template.php');
				break;
			case 'font-tbl':
				$template = new Template(__ASSETS_PATH . '/php/View/Template/FontListingTable.template.php');
				break;
			case 'icons-css':
				$template = new Template(__ASSETS_PATH . '/php/View/Template/Icons.template.php');
				break;
			case 'elem-seg':
				$template = new Template(__ASSETS_PATH . '/php/View/Template/ElementListing.template.php');
				break;
		}
		
		return new StyleguideItemView($model, $currentUser, $template);
	}
}

?>