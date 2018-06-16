<?php

class MoodboardSectionImageFactory
{
	public static function findBySectionImage($sectionID, $imageID)
	{
		$db = new Db();
		
		$query = 'select id from mb_section_image where sectionID = ? and imageID = ?';
		$rows = $db->select($query, 'ii', array(&$sectionID, &$imageID));
		
		if (count($rows) == 1)
		{
			return new MoodboardSectionImage($db, $rows[0]['id']);
		}
		
		return null;
	}
}

?>