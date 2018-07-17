<?php

class StyleguideFontFamilyItemModel extends StyleguideItemModel
{
	public function getData()
	{
		$query = 'select ft.code, f.id, f.name, a.alphabet from sg_font_family ff join sg_font f on f.id = ff.fontID join sg_font_type ft on ft.id = f.typeID left join sg_font_alphabet a on a.id = f.alphabetID where ff.baseID = ?';
		$fontRow = $this->db->select($query, 'i', [&$this->foreignItem->itemID])[0];
		
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
}

?>