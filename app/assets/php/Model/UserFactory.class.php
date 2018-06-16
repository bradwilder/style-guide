<?php

class UserFactory
{
	public static function readByName($name)
	{
		$db = new Db();
		
		$query = 'select id from users where email = ?';
		$rows = $db->select($query, 's', array(&$name));
		
		if (count($rows) == 0)
		{
			return null;
		}
		
		$row = $rows[0];
		
		$user = new User($db, $row['id']);
		$user->read();
		return $user;
	}
}

?>