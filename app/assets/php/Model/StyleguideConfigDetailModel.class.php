<?php

class StyleguideConfigDetailModel extends Model_base
{
	public function getDetailData(string $configDetailType, int $configDetailID)
	{
		if ($configDetailType)
		{
			switch ($configDetailType)
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
					return $this->getSection($configDetailID);
				case 'Subsection':
					return $this->getSubsection($configDetailID);
				case 'Item':
					return $this->getItemData($configDetailID);
			}
		}
		else
		{
			throw new Exception('Type must be set');
		}
	}
	
	private function getColors()
	{
		$colors = [];
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
		$fonts = [];
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
		$uploadsHash = [];
		
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
		$sections = [];
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
	
	private function getSection(int $configDetailID)
	{
		$section = new StyleguideSection($this->db, $configDetailID);
		$section->read();
		$section->readExtra();
		
		$items = new StyleguideConfigDetailItems();
		$items->item = $section;
		$items->items = $section->subsections;
		
		return $items;
	}
	
	private function getSubsection(int $configDetailID)
	{
		$subsection = new StyleguideSubsection($this->db, $configDetailID);
		$subsection->read();
		$subsection->readExtra();
		
		$items = new StyleguideConfigDetailItems();
		$items->item = $subsection;
		$items->items = $subsection->items;
		$items->subitems = $subsection->subSubsections;
		
		return $items;
	}
	
	private function getItemData(int $configDetailID)
	{
		$styleguideItem = new StyleguideItem($this->db, $configDetailID);
		$styleguideItem->read();
		$styleguideItem->readExtra();
		
		$columns = new StyleguideItemColumns($styleguideItem->colXs, $styleguideItem->colSm, $styleguideItem->colMd, $styleguideItem->colLg);
		$itemData = $this->getItem($styleguideItem->type->code, $configDetailID);
		
		return new StyleguideConfigDetailItem($styleguideItem->id, $styleguideItem->name, $styleguideItem->type, $itemData, $columns);
	}
	
	private function getItem(string $itemTypeCode, int $configDetailID)
	{
		$model = StyleguideItemFactory::modelByCode($itemTypeCode);
		$model->itemID = $configDetailID;
		return $model->getConfigData();
	}
}

?>