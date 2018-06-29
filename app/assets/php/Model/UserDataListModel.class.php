<?php

abstract class UserDataListModel extends SimpleModel
{
	protected $userID;
	
	public function setUserID($userID)
	{
		$this->userID = $userID;
	}
}

?>