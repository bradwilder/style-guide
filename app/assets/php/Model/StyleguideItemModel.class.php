<?php

class StyleguideItemModel extends Model_base
{
	public $foreignItem;
	
	public function getData()
	{
		if ($this->foreignItem)
		{
			switch ($this->foreignItem->code)
			{
				case 'color-var-desc':
				case 'color-desc':
					return $this->getColorWithDescriptors();
				case 'color-var':
				case 'color':
					return $this->getColorAndVariants();
				case 'font-fmy':
					return $this->getFontFamily();
				case 'font-tbl':
					return $this->getFontListingTable();
				case 'icons-css':
					return $this->getIcons();
				case 'elem-seg':
					return $this->getElement();
			}
		}
		else
		{
			throw new Exception('ID and code must be set');
		}
	}
	
	public function getColumnData()
	{
		if ($this->foreignItem)
		{
			$row = $this->db->select('select i.colLg, i.colMd, i.colSm, i.colXs from sg_item i join sg_item_type it on it.id = i.typeID where i.id = ? and it.code = ?', 'is', array(&$this->foreignItem->itemID, &$this->foreignItem->code))[0];
			return new StyleguideItemColumns($row['colXs'], $row['colSm'], $row['colMd'], $row['colLg']);
		}
		else
		{
			throw new Exception('ID and code must be set');
		}
	}
	
	private function getColorAndVariants()
	{
		$row = $this->db->select('select c.name, c.hex, c.variant1, c.variant2 from sg_color c join sg_color_item ci on ci.color1ID = c.id where ci.baseID = ?', 'i', array(&$this->foreignItem->itemID))[0];
		
		$colors = array();
		$hex = $row['hex'];
		$name = $row['name'];
		
		if (strpos($this->foreignItem->code, 'var'))
		{
			$variant1 = $row['variant1'];
			$variant2 = $row['variant2'];
			
			if ($variant1)
			{
				$colors []= new ColorTileSegment($name, $variant1, false);
			}
			
			$colors []= new ColorTileSegment($name, $hex, true);
			
			if ($variant2)
			{
				$colors []= new ColorTileSegment($name, $variant2, false);
			}
		}
		else
		{
			$colors []= new ColorTileSegment($name, $hex, true);
		}
		
		return $colors;
	}
	
	private function getColorWithDescriptors()
	{
		$colors = $this->getColorAndVariants();
		
		$colorDescriptors = array();
		$colorName = $colors[0]->name;
		if ($colorName)
		{
			$colorDescriptors []= $colorName;
		}
		
		$rows = $this->db->select('select description from sg_color_descriptor where itemID = ? order by position', 'i', array(&$this->foreignItem->itemID));
		foreach ($rows as $row)
		{
			$colorDescriptors []= $row['description'];
		}
		
		return new ColorDescriptorItem($colors, $colorDescriptors);
	}
	
	private function getFontFamily()
	{
		$query = 'select ft.code, f.id, f.name, a.alphabet from sg_font_family ff join sg_font f on f.id = ff.fontID join sg_font_type ft on ft.id = f.typeID left join sg_font_alphabet a on a.id = f.alphabetID where ff.baseID = ?';
		$fontRow = $this->db->select($query, 'i', array(&$this->foreignItem->itemID))[0];
		
		$fontTypeCode = $fontRow['code'];
		$fontID = $fontRow['id'];
		
		$fontFamily = new FontFamilyItem($fontRow['name'], $fontRow['alphabet'], $fontTypeCode);
		
		if ($fontTypeCode == 'web')
		{
			$fontRow = $this->db->select('select importURL, website from sg_webfont where baseID = ?', 'i', array(&$fontID))[0];
			
			$fontFamily->import = $fontRow['importURL'];
			$fontFamily->url = $fontRow['website'];
		}
		elseif ($fontTypeCode == 'css')
		{
			$fontRow = $this->db->select('select cssFile from sg_cssfont where baseID = ?', 'i', array(&$fontID))[0];
			
			$fontFamily->cssFile = $fontRow['cssFile'];
		}
		
		return $fontFamily;
	}
	
	private function getFontListingTable()
	{
		$listings = array();
		$query = 'select l.id, l.text, f.name from sg_font_listing_table t join sg_font_listing l on l.itemID = t.baseID join sg_font f on f.id = l.fontID where t.baseID = ? order by l.position';
		$listingRows = $this->db->select($query, 'i', array(&$this->foreignItem->itemID));
		foreach ($listingRows as $listingRow)
		{
			$listingID = $listingRow['id'];
			$listingText = $listingRow['text'];
			$fontName = $listingRow['name'];
			
			$listingCSS = array('font-family: ' . $fontName . ';');
			$listingCSSRows = $this->db->select('select c.css from sg_font_listing l join sg_font_listing_css c on c.fontListingID = l.id where l.id = ?', 'i', array(&$listingID));
			foreach ($listingCSSRows as $listingCSSRow)
			{
				$listingCSS []= $listingCSSRow['css'];
			}
			
			$fontListing = new FontListingItem($listingText, $listingCSS);
			$listings []= $fontListing;
		}
		
		return $listings;
	}
	
	private function getIcons()
	{
		$icons = array();
		$iconRows = $this->db->select('select l.html from sg_icon_listing_table t join sg_icon_listing l on l.itemID = t.baseID where t.baseID = ? order by l.position', 'i', array(&$this->foreignItem->itemID));
		foreach ($iconRows as $iconRow)
		{
			$html = $iconRow['html'];
			
			$icons []= $html;
		}
		
		return $icons;
	}
	
	private function getElement()
	{
		$elements = array();
		$query = 'select u1.filePath as u1_file, u1.parentID as u1_path_id, ui1.shortName as u1_short_name, ui1.fullName as u1_full_name, u2.filePath as u2_file, u2.parentID as u2_path_id, ui2.shortName as u2_short_name, ui2.fullName as u2_full_name, u3.filePath as u3_file, u3.parentID as u3_path_id, ui3.shortName as u3_short_name, ui3.fullName as u3_full_name, u4.filePath as u4_file, u4.parentID as u4_path_id, ui4.shortName as u4_short_name, ui4.fullName as u4_full_name, u5.filePath as u5_file, u5.parentID as u5_path_id, ui5.shortName as u5_short_name, ui5.fullName as u5_full_name, u6.filePath as u6_file, u6.parentID as u6_path_id, ui6.shortName as u6_short_name, ui6.fullName as u6_full_name from sg_element e join sg_upload u1 on u1.id = e.upload1ID join sg_upload_file ui1 on ui1.baseID = u1.id left join sg_upload u2 on u2.id = e.upload2ID left join sg_upload_file ui2 on ui2.baseID = u2.id left join sg_upload u3 on u3.id = e.upload3ID left join sg_upload_file ui3 on ui3.baseID = u3.id left join sg_upload u4 on u4.id = e.upload4ID left join sg_upload_file ui4 on ui4.baseID = u4.id left join sg_upload u5 on u5.id = e.upload5ID left join sg_upload_file ui5 on ui5.baseID = u5.id left join sg_upload u6 on u6.id = e.upload6ID left join sg_upload_file ui6 on ui6.baseID = u6.id where e.baseID = ?';
		$elementRow = $this->db->select($query, 'i', array(&$this->foreignItem->itemID))[0];
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
}

?>