<?php

use PHPAuth\Config;
use PHPAuth\Auth;
use PHPAuth\EmailDelegate;
use PHPAuth\SMSDelegate;

class UserModel extends Model_base
{
	public $userID;
	public $email;
	public $phone;
	public $displayName;
	public $groupID;
	public $currUserID;
	public $currUserPassword;
	public $oldPassword;
	public $newPassword;
	public $newPasswordConfirm;
	public $requestIDs;
	public $emailKey;
	public $smsKey;
	
	public function edit()
	{
		if ($this->userID)
		{
			$user = new User($this->db, $this->userID);
			$changeEmailError;
			if ($this->email)
			{
				$config = new Config($this->db);
				$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());

				$changeEmailError = $auth->changeEmail($user, $this->email);
			}
			
			if (!$changeEmailError || !$changeEmailError['error'])
			{
				$user->phone = $this->phone;
				$user->displayName = $this->displayName;
				$user->groupID = $this->groupID;
				$user->write();
			}
			else
			{
				return $changeEmailError['message'];
			}
		}
		else
		{
			throw new Exception('User ID must be set');
		}
	}
	
	public function add()
	{
		if ($this->email && $this->groupID)
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
			
			$registerError = $auth->register($this->email, $this->phone, null, null);
			if (!$registerError['error'])
			{
				$user = UserFactory::readByName($this->email);
				$user->displayName = $this->displayName;
				$user->groupID = $this->groupID;
				$user->write();
			}
			else
			{
				return $registerError['message'];
			}
		}
		else
		{
			throw new Exception('Email and group ID must be set');
		}
	}
	
	public function delete()
	{
		if ($this->userID && $this->currUserID && $this->currUserPassword)
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
			
			$user = new User($this->db, $this->userID);
			$user->read();
			$currUser = new User($this->db, $this->currUserID);
			$currUser->read();
			return $auth->deleteUser($user, $currUser, $this->currUserPassword);
		}
		else
		{
			throw new Exception('User ID, current user ID, and current user password must be set');
		}
	}
	
	public function undelete()
	{
		if ($this->userID)
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
			
			$user = new User($this->db, $this->userID);
			$user->read();
			$undeleteError = $auth->undeleteUser($user);
			if ($undeleteError['error'])
			{
				return $undeleteError['message'];
			}
		}
		else
		{
			throw new Exception('User ID must be set');
		}
	}
	
	public function changePassword()
	{
		if ($this->userID && $this->oldPassword && $this->newPassword && $this->newPasswordConfirm)
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
			
			$user = new User($this->db, $this->userID);
			$user->read();
			$changedError = $auth->changePassword($user, $this->oldPassword, $this->newPassword, $this->newPasswordConfirm);
			if ($changedError['error'])
			{
				return $changedError['message'];
			}
		}
		else
		{
			throw new Exception('User ID and all password fields must be set');
		}
	}
	
	public function activationRequest()
	{
		if ($this->userID)
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
			
			$user = new User($this->db, $this->userID);
			$user->read();
			$sentError = $auth->resendActivation($user);
			if ($sentError['error'])
			{
				return $sentError['message'];
			}
		}
		else
		{
			throw new Exception('User ID must be set');
		}
	}
	
	public function activate()
	{
		if ($this->emailKey && $this->smsKey && $this->newPassword && $this->newPasswordConfirm)
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
			
			$activate = $auth->resetActivate($this->emailKey, $this->newPassword, $this->newPasswordConfirm, $this->smsKey);
			if (!$activate['error'])
			{
				return $activate;
			}
			else
			{
				$error = array('error' => $activate['message']);
				$error['inactive'] = '1';
				$error['expired'] = $activate['expired'];
				if ($error['expired'])
				{
					$error['key'] = $key;
					$error['smsKey'] = $smsKey;
				}
				return $error;
			}
		}
		else
		{
			throw new Exception('Keys and password fields must be set');
		}
	}
	
	public function resendActivation()
	{
		if ($this->emailKey && $this->smsKey)
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
			
			return $auth->resendActivationUser($this->emailKey, $this->smsKey);
		}
		else
		{
			throw new Exception('Email and SMS keys must be set');
		}
	}
	
	public function deleteRequests()
	{
		if ($this->requestIDs)
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());

			$deletedError = $auth->deleteRelatedRequests($this->requestIDs);
			if (!$deletedError)
			{
				return 'Unable to delete the request.';
			}
		}
		else
		{
			throw new Exception('Request IDs must be set');
		}
	}
	
	public function logout()
	{
		$config = new Config($this->db);
		$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
		
		return $auth->logout();
	}
	
	public function login()
	{
		if ($this->email && $this->currUserPassword)
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
			
			$return = $auth->login($this->email, $this->currUserPassword, false);
			if (!$return['error'])
			{
				$return['showMessage'] = false;
				return $return;
			}
			else
			{
				$error = array('error' => $return['message']);
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
		else
		{
			throw new Exception('Email and password must be set');
		}
	}
	
	public function requestReset()
	{
		if ($this->email)
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
			
			$return = $auth->requestReset($this->email);
			$return['showMessage'] = true;
			return $return;
		}
		else
		{
			throw new Exception('Email must be set');
		}
	}
	
	public function resetPassword()
	{
		if ($this->emailKey && $this->smsKey && $this->newPassword && $this->newPasswordConfirm)
		{
			$config = new Config($this->db);
			$auth = new Auth($this->db, $config, new EmailDelegate(), new SMSDelegate());
			
			return $auth->resetPassword($this->emailKey, $this->newPassword, $this->newPasswordConfirm, $this->smsKey);
		}
		else
		{
			throw new Exception('Keys and password fields must be set');
		}
	}
	
	public function getGroups()
	{
		$groups = array();
		$rows = $this->db->select('select id from groups order by name');
		foreach ($rows as $row)
		{
			$group = new Group($this->db, $row['id']);
			$group->read();
			
			$groups []= $group;
		}
		
		return $groups;
	}
}

?>