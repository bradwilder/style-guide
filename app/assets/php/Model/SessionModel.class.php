<?php

use PHPAuth\Config;
use PHPAuth\Auth;
use PHPAuth\EmailDelegate;
use PHPAuth\SMSDelegate;

class SessionModel extends Model_base
{
	private $id;
	
	public function setSessionID($sessionID)
	{
		$this->id = $sessionID;
	}
	
	public function deleteSession()
	{
		if (!$this->id)
		{
			throw new Exception('Session id must be set');
		}
		
		$session = new Session($this->db, $this->id);
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