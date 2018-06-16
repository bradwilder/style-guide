<?php

class SessionListModel extends UserDataListModel
{
	public function getData()
	{
		if (!$this->userID)
		{
			throw new Exception('User ID must be set');
		}
		
		$rows = $this->db->select('select id from sessions where userID = ? order by expire', 'i', array(&$this->userID));
		
		$sessions = array();
		foreach ($rows as $row)
		{
			$session = new Session($this->db, $row['id']);
			$session->read();
			$sessions []= $session;
		}
		
		return $sessions;
	}
}

?>