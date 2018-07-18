<?php

use PHPAuth\Config;
use PHPAuth\Auth;
use PHPAuth\EmailDelegate;
use PHPAuth\SMSDelegate;

class SessionModel extends Model_base
{
	public function deleteSession(int $id)
	{
		$session = new Session($this->db, $id);
		$session->delete();
		
		return true;
	}
	
	public function refreshSession()
	{
		$config = new Config($this->db);
		$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
		
		$auth->refreshSession();
	}
}

?>