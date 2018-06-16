<?php

class PageModel extends Model_base
{
	public $pageTitle;
	public $pageCode;
	public $useMenu;
	public $useTOC;
	public $fullHeight;
	
	public function setUseTOC()
	{
		$rows = $this->db->select('select value from page_options where code = ? and setting = "showTOC"', 's', array(&$this->pageCode));
		if (count($rows) > 0)
		{
			$this->useTOC = ($rows[0]['value'] == 1);
		}
		else
		{
			$this->useTOC = false;
		}
	}
}

?>