<?php

class StyleguideFontFamilyItemModel extends StyleguideItemModel
{
	public function getData()
	{
		if ($this->itemID)
		{
			$query = 'select ft.code, f.id, f.name, a.alphabet from sg_font_family ff join sg_font f on f.id = ff.fontID join sg_font_type ft on ft.id = f.typeID left join sg_font_alphabet a on a.id = f.alphabetID where ff.baseID = ?';
			$fontRow = $this->db->select($query, 'i', [&$this->itemID])[0];
			
			$fontTypeCode = $fontRow['code'];
			$fontID = $fontRow['id'];
			
			$fontFamily = new FontFamilyItem($fontRow['name'], $fontRow['alphabet'], $fontTypeCode);
			
			if ($fontTypeCode == 'web')
			{
				$fontRow = $this->db->select('select importURL, website from sg_webfont where baseID = ?', 'i', [&$fontID])[0];
				
				$fontFamily->import = $fontRow['importURL'];
				$fontFamily->url = $fontRow['website'];
			}
			elseif ($fontTypeCode == 'css')
			{
				$fontRow = $this->db->select('select cssFile from sg_cssfont where baseID = ?', 'i', [&$fontID])[0];
				
				$fontFamily->cssFile = $fontRow['cssFile'];
			}
			
			return $fontFamily;
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
			$fontFamily = new StyleguideFontFamilyItem($this->db, $this->itemID);
			$fontFamily->read();
			
			$font = new Font($this->db, $fontFamily->fontID);
			$font->read();
			$font->readExtra();
			
			return new StyleguideConfigDetailFontFamilyItem($font->id, $font->name, $font->alphabet->name);
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
}

?>