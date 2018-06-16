<?php

class UserListItem
{
	public $user;
	public $group;
	
	public function __construct($user, $group)
	{
		$this->user = $user;
		$this->group = $group;
	}
}

?>