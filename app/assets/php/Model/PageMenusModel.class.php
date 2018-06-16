<?php

class PageMenusModel extends Model_base
{
	public $brandName;
	public $pageCode;
	
	public function setBrandName()
	{
		$rows = $this->db->select('select companyName from branding');
		if (count($rows) > 0)
		{
			$this->brandName = $rows[0]['companyName'];
		}
		else
		{
			$this->brandName = 'Lorem Ipsum';
		}
	}
}

?>