<?php

class UserListModel extends Model_base
{
	public function getUsers()
	{
		$userListItems = Array();
		$rows = $this->db->select('select id from users order by email');
		foreach ($rows as $row)
		{
			$user = new User($this->db, $row['id']);
			$user->read();
			$user->readExtra();
			
			$group = new Group($this->db, $user->groupID);
			$group->read();
			
			$userListItem = new UserListItem($user, $group);
			$userListItems []= $userListItem;
		}
		
		return $userListItems;
	}
}

?>