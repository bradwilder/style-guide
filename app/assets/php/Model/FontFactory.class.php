<?php

class FontFactory
{
	public static function readFontByID($fontID)
	{
		$db = new Db();
		
		$query = 'select ft.code from sg_font f join sg_font_type ft on ft.id = f.typeID where f.id = ?';
		$fontTypeCode = $db->select($query, 'i', array(&$fontID))[0]['code'];
		
		$font;
		switch ($fontTypeCode)
		{
			case WebFont::$code:
				$font = new WebFont($db, $fontID);
				break;
			case CSSFont::$code:
				$font = new CSSFont($db, $fontID);
				break;
		}
		
		$font->read();
		return $font;
	}
	
	public static function createByCode($fontTypeCode)
	{
		$db = new Db();
		
		switch ($fontTypeCode)
		{
			case WebFont::$code:
				return new WebFont($db);
			case CSSFont::$code:
				return new CSSFont($db);
		}
	}
}

?>