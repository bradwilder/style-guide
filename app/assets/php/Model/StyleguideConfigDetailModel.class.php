<?php

class StyleguideConfigDetailModel extends Model_base
{
	public $configDetailType;
	public $configDetailID;
	
	public function getDetailData()
	{
		if ($this->configDetailType)
		{
			switch ($this->configDetailType)
			{
				case 'Colors':
					return $this->getColors();
				case 'Fonts':
					return $this->getFonts();
				case 'Uploads':
					return $this->getUploads();
				case 'Sections':
					return $this->getSections();
				case 'Section':
					return $this->getSection();
				case 'Subsection':
					return $this->getSubsection();
				case 'Item':
					return $this->getItemData();
			}
		}
		else
		{
			throw new Exception('Type must be set');
		}
	}
	
	public function getItemCode()
	{
		if ($this->configDetailID && $this->configDetailType && $this->configDetailType == 'Item')
		{
			$styleguideItem = new StyleguideItem($this->db, $this->configDetailID);
			$styleguideItem->read();
			$styleguideItem->readExtra();
			
			return $styleguideItem->type->code;
		}
		else
		{
			throw new Exception('ID and type must be set for "Item" type');
		}
	}
	
	private function getColors()
	{
		$colors = array();
		$rows = $this->db->select('select id from sg_color order by name');
		foreach ($rows as $row)
		{
			$color = new Color($this->db, $row['id']);
			$color->read();
			
			$colors []= $color;
		}
		
		$defaultColor;
		$rows = $this->db->select('select color_id from sg_color_default');
		if (count($rows) > 0)
		{
			$defaultColor = $rows[0]['color_id'];
		}
		
		$items = new StyleguideConfigDetailItems();
		$items->item = $defaultColor;
		$items->items = $colors;
		
		return $items;
	}
	
	private function getFonts()
	{
		$fonts = array();
		$rows = $this->db->select('select id from sg_font order by name');
		foreach ($rows as $row)
		{
			$font = new Font($this->db, $row['id']);
			$font->read();
			$font->readExtra();
			
			$fonts []= $font;
		}
		
		$items = new StyleguideConfigDetailItems();
		$items->items = $fonts;
		
		return $items;
	}
	
	private function getUploads()
	{
		$uploadsHash = array();
		
		$uploads = new UploadItem(null, 'Uploads', true);
		$uploadsHash[''] = $uploads;
		
		$rows = $this->db->select('select u.id, case when t.code = "folder" then 1 else 0 end as isFolder from sg_upload u join sg_upload_type t on t.id = u.typeID order by u.parentID is null desc, u.parentID asc, isFolder desc, u.filePath');
		foreach ($rows as $row)
		{
			$upload = new Upload($this->db, $row['id']);
			$upload->read();
			$upload->readExtra();
			
			$uploadItem = new UploadItem($upload->id, $upload->filePath, $upload->isFolder());
			
			$parent = $uploadsHash[$upload->parentID];
			$parent->addChild($uploadItem);
			
			$uploadsHash[$upload->id] = $uploadItem;
		}
		
		$items = new StyleguideConfigDetailItems();
		$items->item = $uploads;
		
		return $items;
	}
	
	private function getSections()
	{
		$sections = array();
		$rows = $this->db->select('select id from sg_section order by position');
		foreach ($rows as $row)
		{
			$section = new StyleguideSection($this->db, $row['id']);
			$section->read();
			
			$sections []= $section;
		}
		
		$items = new StyleguideConfigDetailItems();
		$items->items = $sections;
		
		return $items;
	}
	
	private function getSection()
	{
		if ($this->configDetailID)
		{
			$section = new StyleguideSection($this->db, $this->configDetailID);
			$section->read();
			$section->readExtra();
			
			$items = new StyleguideConfigDetailItems();
			$items->item = $section;
			$items->items = $section->subsections;
			
			return $items;
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
	
	private function getSubsection()
	{
		if ($this->configDetailID)
		{
			$subsection = new StyleguideSubsection($this->db, $this->configDetailID);
			$subsection->read();
			$subsection->readExtra();
			
			$items = new StyleguideConfigDetailItems();
			$items->item = $subsection;
			$items->items = $subsection->items;
			$items->subitems = $subsection->subSubsections;
			
			return $items;
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
	
	private function getItemData()
	{
		if ($this->configDetailID)
		{
			$styleguideItem = new StyleguideItem($this->db, $this->configDetailID);
			$styleguideItem->read();
			$styleguideItem->readExtra();
			
			$columns = new StyleguideItemColumns($styleguideItem->colXs, $styleguideItem->colSm, $styleguideItem->colMd, $styleguideItem->colLg);
			$itemData = $this->getItem($styleguideItem->type->code);
			
			return new StyleguideConfigDetailItem($styleguideItem->id, $styleguideItem->name, $styleguideItem->type, $itemData, $columns);
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
	
	private function getItem($itemTypeCode)
	{
		switch ($itemTypeCode)
		{
			case 'color-var-desc':
			case 'color-var':
			case 'color-desc':
			case 'color':
			case 'color-pal':
				return $this->getColorItem();
			case 'font-fmy':
				return $this->getFontFamilyItem();
			case 'font-tbl':
				return $this->getFontTableItem();
			case 'icons-css':
				return $this->getIconTableItem();
			case 'elem-seg':
				return $this->getSegmentedElementItem();
		}
	}
	
	private function getColorItem()
	{
		$colorItem = new StyleguideColorItem($this->db, $this->configDetailID);
		$colorItem->read();
		$colorItem->readItemData();
		
		return new StyleguideConfigDetailColorDescriptorItem($colorItem->colors, $colorItem->descriptors);
	}
	
	private function getFontFamilyItem()
	{
		$fontFamily = new StyleguideFontFamilyItem($this->db, $this->configDetailID);
		$fontFamily->read();
		
		$font = new Font($this->db, $fontFamily->fontID);
		$font->read();
		$font->readExtra();
		
		return new StyleguideConfigDetailFontFamilyItem($font->id, $font->name, $font->alphabet->name);
	}
	
	private function getFontTableItem()
	{
		$fontTable = new StyleguideFontTableItem($this->db, $this->configDetailID);
		$fontTable->read();
		$fontTable->readItemData();
		
		$fontTableItem = new StyleguideConfigDetailFontTableItem();
		foreach ($fontTable->listings as $listing)
		{
			$fontListingItem = new StyleguideConfigDetailFontTableListing($listing->id, $listing->text);
			foreach ($listing->cssList as $css)
			{
				$fontListingItem->addCSS($css);
			}
			
			$fontTableItem->addListing($fontListingItem);
		}
		
		return $fontTable;
	}
	
	private function getIconTableItem()
	{
		$row = $this->db->select('select f.id, f.name from sg_icon_listing_table t join sg_font f on f.id = t.fontID where t.baseID = ?', 'i', array(&$this->configDetailID))[0];
		
		$iconSet = new StyleguideConfigDetailIconSet($row['id'], $row['name']);
		
		$iconTable = new StyleguideIconTableItem($this->db, $this->configDetailID);
		$iconTable->read();
		$iconTable->readItemData();
		
		$iconTableItem = new StyleguideConfigDetailIconTableItem($iconSet);
		foreach ($iconTable->listings as $listing)
		{			
			$iconTableItem->addListing($listing);
		}
		
		return $iconTableItem;
	}
	
	private function getSegmentedElementItem()
	{
		$element = new StyleguideElementItem($this->db, $this->configDetailID);
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
}

?>