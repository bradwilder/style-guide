<?php

namespace PHPAuth;

use Db;

class CurrentUser
{
	public $id;
	public $email;
	public $displayName;
	public $roles = [];
	
	public function addRole($role)
	{
		$this->roles []= $role;
	}
}

class Authentication
{
	private $db;
	private $auth;
	
	public function __construct(Db $db)
	{
		$this->db = $db;
		$this->auth = new Auth($db, new Config($db), new EmailDelegate(), new SMSDelegate());
	}
	
	private function getCurrentUser()
	{
		$this->auth->refreshSession();
		
		$user = $this->auth->findUser();
		
		$currentUser = new CurrentUser();
		$currentUser->id = $user->id;
		$currentUser->email = $user->email;
		$currentUser->displayName = $user->displayName;
		
		$query = 'select r.name from users u join groups g on g.id = u.groupID join group_role gr on gr.groupID = g.id join role r on r.id = gr.role_id where u.id = ?';
		$rows = $this->db->select($query, 'i', [&$currentUser->id]);
		
		foreach ($rows as $row)
		{
			$currentUser->addRole($row['name']);
		}
		
		return $currentUser;
	}
	
	public function authenticate($redirectToLogin = false, $redirectPath = null)
	{
		$isLogged = $this->auth->isLogged();
		if ($isLogged == 0)
		{
			return $this->getCurrentUser();
		}
		else if ($redirectToLogin)
		{
			$param;
			if ($isLogged == 2)
			{
				$param = '?message=' . htmlentities('You have been logged out.');
			}
			else if ($isLogged == 1)
			{
				$param = '?error=' . htmlentities('You are not logged in.');
			}
			
			if ($redirectPath)
			{
				$param .= '&redir=' . $redirectPath;
			}
			
			header('Location: /login' . $param);
		}
		
		return null;
	}
	
	public function authorize($redirectToLogin = false, $requiredRole = null, $redirectPath = null)
	{
		$currentUser = $this->authenticate($redirectToLogin, $redirectPath);
		if (!$currentUser)
		{
			return null;
		}
		
		if ($requiredRole && !in_array($requiredRole, $currentUser->roles))
		{
			setReturnHeaders(401, 'Unauthorized', ['error' => 'You do not have permission to access this page.']);
			return null;
		}
		
		return $currentUser;
	}
}

?>