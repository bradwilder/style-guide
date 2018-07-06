<?php

class SMSPageModel extends PageModel
{
	public $smsEnabled;
	
	public function setSMSEnabled()
	{
		$rows = $this->db->select('select value from config where setting = "sms"');
		if (count($rows) > 0)
		{
			$this->smsEnabled = ($rows[0]['value'] == 1);
		}
		else
		{
			$this->smsEnabled = false;
		}
	}
}

?>