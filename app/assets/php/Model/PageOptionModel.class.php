<?php

class PageOptionModel extends Model_base
{
	public function getShowTOCOptions()
	{
		$options = [];
		$rows = $this->db->select('select code, value from page_options where setting = "showTOC"');
		foreach ($rows as $row)
		{
			$pageOptionListItem = new PageOptionItem($row['code'], $row['value']);
			$options []= $pageOptionListItem;
		}
		
		return $options;
	}
	
	public function setShowTOCOption(string $pageCode, string $value)
	{
		$this->db->query('update page_options set value = ? where setting = "showTOC" and code = ?', 'ss', [&$value, &$pageCode]);
	}
}

?>