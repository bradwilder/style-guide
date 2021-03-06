<?php

class StyleguidePageModel extends PageModel
{
	public function getWebFontURLs()
	{
		$urls = [];
		$rows = $this->db->select('select importURL from sg_webfont');
		foreach ($rows as $row)
		{
			$urls []= $row['importURL'];
		}
		
		return $urls;
	}
	
	public function getCSSFontFiles()
	{
		$files = [];
		$rows = $this->db->select('select cssFile from sg_cssfont');
		foreach ($rows as $row)
		{
			$files []= $row['cssFile'];
		}
		
		return $files;
	}
}

?>