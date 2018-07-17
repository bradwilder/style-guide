<?php

class RequestListModel extends UserDataListModel
{
	public function getData()
	{
		if (!$this->userID)
		{
			throw new Exception('User ID must be set');
		}
		
		return $this->db->select('select GROUP_CONCAT(id SEPARATOR ",") as id, expire, GROUP_CONCAT(type SEPARATOR ", ") as types from requests where userID = ? group by emailKey, expire order by expire', 'i', [&$this->userID]);
	}
}

?>