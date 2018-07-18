<?php

use PHPAuth\Config;
use PHPAuth\Auth;
use PHPAuth\EmailDelegate;
use PHPAuth\SMSDelegate;

class UserModel extends Model_base
{
	public function nameExists(string $name, int $selfID = null)
	{
		return User::nameExists($this->db, $name, $selfID);
	}
	
	public function edit(int $userID, string $email = null, string $phone = null, string $displayName = null, int $groupID = null)
	{
		$user = new User($this->db, $userID);
		$changeEmailError;
		if ($email)
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());

			$changeEmailError = $auth->changeEmail($user, $email);
		}
		
		if (!$changeEmailError || !$changeEmailError['error'])
		{
			$user->phone = $phone;
			$user->displayName = $displayName;
			$user->groupID = $groupID;
			$user->write();
		}
		else
		{
			return $changeEmailError['message'];
		}
	}
	
	public function add(string $email = null, int $groupID, string $phone = null, string $displayName = null)
	{
		$config = new Config($this->db);
		$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
		
		$registerError = $auth->register($email, $phone, null, null);
		if (!$registerError['error'])
		{
			$user = UserFactory::readByName($email);
			$user->displayName = $displayName;
			$user->groupID = $groupID;
			$user->write();
		}
		else
		{
			return $registerError['message'];
		}
	}
	
	public function delete(int $userID, int $currUserID, string $currUserPassword)
	{
		$config = new Config($this->db);
		$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
		
		$user = new User($this->db, $userID);
		$user->read();
		$currUser = new User($this->db, $currUserID);
		$currUser->read();
		return $auth->deleteUser($user, $currUser, $currUserPassword);
	}
	
	public function undelete(int $userID)
	{
		$config = new Config($this->db);
		$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
		
		$user = new User($this->db, $userID);
		$user->read();
		$undeleteError = $auth->undeleteUser($user);
		if ($undeleteError['error'])
		{
			return $undeleteError['message'];
		}
	}
	
	public function changePassword(int $userID, string $oldPassword, string $newPassword, string $newPasswordConfirm)
	{
		$config = new Config($this->db);
		$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
		
		$user = new User($this->db, $userID);
		$user->read();
		$changedError = $auth->changePassword($user, $oldPassword, $newPassword, $newPasswordConfirm);
		if ($changedError['error'])
		{
			return $changedError['message'];
		}
	}
	
	public function activationRequest(int $userID)
	{
		$config = new Config($this->db);
		$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
		
		$user = new User($this->db, $userID);
		$user->read();
		$sentError = $auth->resendActivation($user);
		if ($sentError['error'])
		{
			return $sentError['message'];
		}
	}
	
	public function activate(string $emailKey, string $newPassword, string $newPasswordConfirm, string $smsKey = null)
	{
		if ($smsKey || !$this->getSMSEnabled())
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
			
			$activate = $auth->resetActivate($emailKey, $newPassword, $newPasswordConfirm, $smsKey);
			if (!$activate['error'])
			{
				return $activate;
			}
			else
			{
				$error = ['error' => $activate['message']];
				$error['inactive'] = '1';
				$error['expired'] = $activate['expired'];
				return $error;
			}
		}
		else
		{
			throw new Exception('SMS key must be provided');
		}
	}
	
	public function resendActivation(string $emailKey, string $smsKey = null)
	{
		if ($smsKey || !$this->getSMSEnabled())
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
			
			return $auth->resendActivationUser($emailKey, $smsKey);
		}
		else
		{
			throw new Exception('SMS key must be provided');
		}
	}
	
	public function deleteRequests($requestIDs)
	{
		$config = new Config($this->db);
		$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());

		$deletedError = $auth->deleteRelatedRequests($requestIDs);
		if (!$deletedError)
		{
			return 'Unable to delete the request.';
		}
	}
	
	public function logout()
	{
		$config = new Config($this->db);
		$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
		
		return $auth->logout();
	}
	
	public function login(string $email, string $password)
	{
		$config = new Config($this->db);
		$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
		
		$return = $auth->login($email, $password, false);
		if (!$return['error'])
		{
			$return['showMessage'] = false;
			return $return;
		}
		else
		{
			$error = ['error' => $return['message']];
			if ($return['inactive'])
			{
				$error['inactive'] = '1';
			}
			
			if ($error['reset'])
			{
				$error['reset'] = '1';
			}
			
			return $error;
		}
	}
	
	public function requestReset(string $email)
	{
		$config = new Config($this->db);
		$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
		
		$return = $auth->requestReset($email);
		$return['showMessage'] = true;
		return $return;
	}
	
	public function resetPassword(string $emailKey, string $newPassword, string $newPasswordConfirm, string $smsKey = null)
	{
		if ($smsKey || !$this->getSMSEnabled())
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
			
			return $auth->resetPassword($emailKey, $newPassword, $newPasswordConfirm, $smsKey);
		}
		else
		{
			throw new Exception('Keys and password fields must be set');
		}
	}
	
	public function getGroups()
	{
		$groups = [];
		$rows = $this->db->select('select id from groups order by name');
		foreach ($rows as $row)
		{
			$group = new Group($this->db, $row['id']);
			$group->read();
			
			$groups []= $group;
		}
		
		return $groups;
	}
	
	private function getSMSEnabled()
	{
		$rows = $this->db->select('select value from config where setting = "sms"');
		if (count($rows) > 0)
		{
			return ($rows[0]['value'] == 1);
		}
		
		return false;
	}
}

?>