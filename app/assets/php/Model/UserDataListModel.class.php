<?php

abstract class UserDataListModel extends Model_base
{
	protected $userID;
	
	public function setUserID($userID)
	{
		$this->userID = $userID;
	}
	
	public abstract function getData();
}

?>