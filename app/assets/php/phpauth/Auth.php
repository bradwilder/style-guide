<?php

namespace PHPAuth;

use UserFactory;
use User;
use Session;
use Requests;
use Attempt;
use Db;

class Auth
{
    private $config;
    private $strings;
    private $db;
    private $emailDelegate;
    private $smsDelegate;
    private $cookies;
    
    public function __construct(Db $db, $config, EmailDelegate_i $emailDelegate = null, SMSDelegate_i $smsDelegate = null, CookieDelegate_i $cookies = null)
    {
        $this->db = $db;
        $this->config = $config;
        $this->emailDelegate = $emailDelegate;
        $this->smsDelegate = $smsDelegate;
        
        if ($cookies)
        {
            $this->cookies = $cookies;
        }
        else
        {
            $this->cookies = new CookieDelegate();
        }
        
        if (version_compare(phpversion(), '5.4.0', '<'))
		{
            die('PHP 5.4.0 required for PHPAuth.');
        }
		
        if (version_compare(phpversion(), '5.5.0', '<'))
		{
            require("files/password.php");
        }
		
        require "strings.php";
        $this->strings = $strings;
		
        date_default_timezone_set($this->config->site_timezone);
    }
	
    public function login(string $email, string $password, bool $remember = false)
    {
        $return['error'] = true;
		
        if ($this->isBlocked())
		{
			$return['message'] = $this->strings["user_blocked"];
			return $return;
		}
		
        $user = UserFactory::readByName($email);
        if (!$user)
		{
            $this->addAttempt();
            $return['message'] = $this->strings["email_password_incorrect"];
            return $return;
        }
		
		if ($user->isDeleted)
		{
            $this->addAttempt();
            $return['message'] = $this->strings["user_deleted"];
            return $return;
        }
        else if (!$user->isActive)
		{
            $this->addAttempt();
			$return['inactive'] = 1;
            $return['message'] = $this->strings["account_inactive"];
            return $return;
        }
		else if ($user->resetNeeded)
		{
            $this->addAttempt();
			$return['reset'] = 1;
            $return['message'] = $this->strings["password_must_be_set"];
            return $return;
        }
		else if (!password_verify($password, $user->password))
		{
            $this->addAttempt();
            $return['message'] = $this->strings["email_password_incorrect"];
            return $return;
        }
		
        $session = $this->addSession($user, $remember);
		
        $this->deleteRequests($user);
        $this->deleteAttempts($this->getIp(), true);
		
        $return['error'] = false;
        $return['message'] = $this->strings["logged_in"];
        $return['hash'] = $session->hash;
		
        return $return;
    }
	
    public function logout(string $hash = null)
    {
        if (!isset($hash))
        {
            $hash = $this->getSessionHash();
        }
		
        return $this->deleteSession($hash);
    }
	
    // Creates a new user, adds them to database, and either activates them or sends and activation email (if enabled) plus an sms (if enabled)
    public function register(string $email, string $phone = null, string $password = null, string $repeatpassword = null)
    {
        $return['error'] = true;
        
		if ($this->isBlocked())
		{
			$return['message'] = $this->strings["user_blocked"];
			return $return;
		}
        
        $reset;
        if ($password || $repeatpassword)
        {
            if ($password !== $repeatpassword)
            {
                $return['message'] = $this->strings["password_nomatch"];
                return $return;
            }
            
            $validatePassword = $this->validatePassword($password);
            if ($validatePassword['error'])
            {
                $return['message'] = $validatePassword['message'];
                return $return;
            }
            
            $reset = false;
        }
        else
        {
            if (!$this->config->email)
    		{
                $return['message'] = $this->strings["password_must_be_set"];
                return $return;
            }
            
            $password = $this->getRandomKey();
            $reset = true;
        }
		
        $validateEmail = $this->validateEmail($email);
        if ($validateEmail['error'])
		{
            $return['message'] = $validateEmail['message'];
            return $return;
        }
		
        if (User::nameExists($email))
		{
            $this->addAttempt();
            $return['message'] = $this->strings["email_taken"];
            return $return;
        }
        
        $user;
        $this->addUser($email, $password, $user, $phone, $reset);
        
        if ($this->config->email)
		{
            $requestTypes = ['activation'];
			if ($reset)
			{
                array_unshift($requestTypes, 'reset');
            }
            
            $addRequest = $this->addRequest($user, $requestTypes);
            if ($addRequest['error'])
			{
				$return['message'] = $addRequest['message'];
                return $return;
            }
        }
		
        $return['error'] = false;
        $return['message'] = ($user->isActive ? $this->strings["register_success"] : $this->strings["register_success_emailmessage_suppressed"]);
        return $return;
    }
	
    private function addUser(string $email, string $password, User &$user = null, string $phone = null, bool $reset)
    {
        $user = new User($this->db);
        
        $user->email = $email;
        $user->phone = $phone;
        $user->password = $this->createHash($password);
        $user->isActive = !($this->config->email == 1);
		
        if (isset($reset))
        {
            $user->resetNeeded = $reset;
        }
        
        $user->write();
    }
	
    public function resendActivation(User $user, bool $alsoReset = null)
    {
        $return['error'] = true;
        
		if ($this->isBlocked())
		{
			$return['message'] = $this->strings["user_blocked"];
			return $return;
		}
		
        if ($user->isActive)
		{
            $this->addAttempt();
            $return['message'] = $this->strings["already_activated"];
            return $return;
        }
		
		if (!isset($alsoReset))
		{
			$alsoReset = $user->resetNeeded;
        }
		
		$options = ["activation"];
		if ($alsoReset)
		{
			array_unshift($options, "reset");
        }
		$addRequest = $this->addRequest($user, $options);
        if ($addRequest['error'])
		{
            $this->addAttempt();
            $return['message'] = $addRequest['message'];
            return $return;
        }
		
		if ($alsoReset)
		{
            $user->password = $this->createHash($this->getRandomKey());
            $user->resetNeeded = true;
            $user->write();
		}
		
        $return['error'] = false;
        $return['message'] = $this->strings["activation_sent"];
        return $return;
    }
	
	public function resendActivationUser(string $emailKey, string $smsKey = null)
	{
		$return['error'] = true;
		
        $request;
        $requestReturn = $this->getRequest($emailKey, $smsKey, "activation", $request, true);
        if ($requestReturn['error'])
		{
            $this->addAttempt();
            $return['message'] = $requestReturn['message'];
            return $return;
        }
        
        $user = new User($this->db, $request->userID);
        if (!$user)
		{
            $this->addAttempt();
            $return['message'] = $this->strings["user_not_found"];
            return $return;
        }
        
        $user->read();
		
		return $this->resendActivation($user);
	}
	
    public function requestReset(string $email)
    {
        $return['error'] = true;
        $standardReply = $this->strings["reset_requested"];
        
		if ($this->isBlocked())
		{
			$return['message'] = $this->strings["user_blocked"];
			return $return;
		}
		
		$validateEmail = $this->validateEmail($email);
        if ($validateEmail['error'])
		{
            $return['message'] = $validateEmail['message'];
            return $return;
        }
		
		$user = UserFactory::readByName($email);
		if (!$user)
		{
            $this->addAttempt();
            if ($this->config->email_send_disguise > 0)
            {
                sleep($this->config->email_send_disguise);
                $return['message'] = $standardReply;
                $return['error'] = false;
            }
            else
            {
                $return['message'] = $this->strings["email_incorrect"];
            }
            return $return;
		}
		
		$addRequest = $this->addRequest($user, ["reset"]);
        if ($addRequest['error'])
		{
            $this->addAttempt();
            if ($this->config->email_send_disguise > 0)
            {
                sleep($this->config->email_send_disguise);
                $return['message'] = $standardReply;
                $return['error'] = false;
            }
            else
            {
                $return['message'] = $addRequest['message'];
            }
            return $return;
        }
		
        $return['error'] = false;
        $return['message'] = $standardReply;
        return $return;
    }
    
    public function activate(string $emailKey, string $password, string $smsKey = null)
    {
        $return['error'] = true;
        $return['expired'] = false;
        
		if ($this->isBlocked())
		{
			$return['message'] = $this->strings["user_blocked"];
			return $return;
		}
		
        $request;
        $requestReturn = $this->getRequest($emailKey, $smsKey, "activation", $request);
        if ($requestReturn['error'])
		{
            $this->addAttempt();
            $return['expired'] = $requestReturn['expired'];
            $return['message'] = $requestReturn['message'];
            return $return;
        }
		
        $user = new User($this->db, $request->userID);
		if (!$user)
		{
            $this->addAttempt();
            $request->delete();
            $return['message'] = $this->strings["user_not_found"];
            return $return;
        }
        
        $user->read();
        
        if ($user->isDeleted)
		{
            $this->addAttempt();
            $return['message'] = $this->strings["user_deleted"];
            return $return;
        }
        else if ($user->isActive)
		{
            $request->delete();
            $return['message'] = $this->strings["already_activated"];
            return $return;
        }
		else if ($user->resetNeeded)
		{
			$return['reset'] = 1;
            $return['message'] = $this->strings["password_must_be_set"];
            return $return;
        }
		else if (!password_verify($password, $user->password))
		{
            $this->addAttempt();
            $return['message'] = $this->strings["email_password_incorrect"];
            return $return;
        }
        
        $user->isActive = true;
        $user->write();
		
        $request->delete();
		
        $return['error'] = false;
        $return['message'] = $this->strings["account_activated"];
        return $return;
    }
    
    public function resetPassword(string $emailKey, string $password, string $repeatpassword, string $smsKey = null)
    {
        $return['error'] = true;
        $return['expired'] = false;
        
		if ($this->isBlocked())
		{
			$return['message'] = $this->strings["user_blocked"];
			return $return;
		}
		
        $validatePassword = $this->validatePassword($password);
        if ($validatePassword['error'])
		{
            $return['message'] = $validatePassword['message'];
            return $return;
        }
		
        if ($password !== $repeatpassword)
		{
            $return['message'] = $this->strings["newpassword_nomatch"];
            return $return;
        }
		
        $request;
        $requestReturn = $this->getRequest($emailKey, $smsKey, "reset", $request);
		if ($requestReturn['error'])
		{
            $this->addAttempt();
            $return['expired'] = $requestReturn['expired'];
            $return['message'] = $requestReturn['message'];
            return $return;
        }
		
        $user = new User($this->db, $request->userID);
        if (!$user)
		{
            $this->addAttempt();
            $request->delete();
            $return['message'] = $this->strings["user_not_found"];
            return $return;
        }
        
        $user->read();
		
		if (password_verify($password, $user->password))
		{
            $this->addAttempt();
            $return['message'] = $this->strings["newpassword_match"];
            return $return;
        }
		
        $user->password = $this->createHash($password);
        $user->resetNeeded = false;
        $user->write();
		
        $request->delete();
        
		$return['error'] = false;
        $return['message'] = $this->strings["password_reset"];
        return $return;
    }
	
	public function resetActivate(string $emailKey, string $password, string $repeatpassword, string $smsKey = null)
	{
        $return['error'] = true;
        $return['expired'] = false;
        
		if ($this->isBlocked())
		{
			$return['message'] = $this->strings["user_blocked"];
			return $return;
		}
		
        $validatePassword = $this->validatePassword($password);
        if ($validatePassword['error'])
		{
            $return['message'] = $validatePassword['message'];
            return $return;
        }
		
        if ($password !== $repeatpassword)
		{
            $return['message'] = $this->strings["newpassword_nomatch"];
            return $return;
        }
        
        $activationRequest;
        $activationRequestReturn = $this->getRequest($emailKey, $smsKey, "activation", $activationRequest);
        if ($activationRequestReturn['error'])
		{
            $this->addAttempt();
            $return['expired'] = $activationRequestReturn['expired'];
            $return['message'] = $activationRequestReturn['message'];
            return $return;
        }
        
        $resetRequest;
        $resetRequestReturn = $this->getRequest($emailKey, $smsKey, "reset", $resetRequest);
		if ($resetRequestReturn['error'])
		{
            $this->addAttempt();
            $return['expired'] = $resetRequestReturn['expired'];
            $return['message'] = $resetRequestReturn['message'];
            return $return;
        }
		
        $user = new User($this->db, $resetRequest->userID);
        if (!$user)
		{
            $this->addAttempt();
            $resetRequest->delete();
            $activationRequest->delete();
            $return['message'] = $this->strings["user_not_found"];
            return $return;
        }
        
        $user->read();
		
        if ($user->isDeleted)
		{
            $this->addAttempt();
            $return['message'] = $this->strings["user_deleted"];
            return $return;
        }
        else if ($user->isActive)
		{
            $request->delete();
            $return['message'] = $this->strings["already_activated"];
            return $return;
        }
		
        $user->password = $this->createHash($password);
        $user->resetNeeded = false;
        $user->isActive = true;
        $user->write();
		
        $resetRequest->delete();
        $activationRequest->delete();
		
        $return['error'] = false;
        $return['message'] = $this->strings["account_activated"];
        return $return;
	}
	
    private function createHash(string $password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $this->config->bcrypt_cost]);
    }
	
    private function addSession(User $user, bool $remember)
    {
        $this->deleteSessions($user->id);
		
        $session = new Session($this->db);
        $session->userID = $user->id;
        $session->hash = $this->createSessionHash();
        $session->expire = strtotime($remember ? $this->config->cookie_remember : $this->config->cookie_forget);
        $session->ip = $this->getIp();
        $session->agent = $_SERVER['HTTP_USER_AGENT'];
        $session->cookieCRC = $this->createCookieCRC($session->hash);
        $session->write();
        
        $this->cookies->setCookie('authID', $session->hash, 0, '/');
        
        return $session;
    }
    
    private function createSessionHash()
    {
        return sha1($this->config->site_key . microtime());
    }
    
    private function createCookieCRC(string $sessionHash)
    {
        return sha1($sessionHash . $this->config->site_key);
    }
    
    private function deleteSessions(int $userID)
    {
        $query = "select id from sessions WHERE userID = ?";
        $rows = $this->db->select($query, 'i', [&$userID]);
        
        foreach ($rows as $row)
        {
            $session = new Session($this->db, $row['id']);
            $session->delete();    
        }
    }
	
    private function deleteSession(string $hash)
    {
        $session = $this->findSessionByHash($hash);
        if ($session)
        {
            $session->delete();
            return true;
        }
        
        return false;
    }
    
    private function findSessionByHash(string $hash)
    {
        $query = "select id from sessions WHERE hash = ?";
        $rows = $this->db->select($query, 's', [&$hash]);
        
        if (count($rows) == 1)
        {
            $row = $rows[0];
            $session = new Session($this->db, $row['id']);
            $session->read();
            return $session;   
        }
    }
	
	public function refreshSession(string $hash = null, bool $remember = false)
	{
        if (!$hash)
        {
            $hash = $this->getSessionHash();
        }
        
        $session = $this->findSessionByHash($hash);
        if ($session)
        {
            $expireTime = strtotime($remember ? $this->config->cookie_remember : $this->config->cookie_forget);
            
            $this->cookies->setCookie('serverTime', time() * 1000, $expireTime + 20, '/');
            $this->cookies->setCookie('sessionExpiry', $expireTime * 1000, $expireTime + 20, '/');
            
            $session->expire = $expireTime;
            $session->write();
        }
    }
	
	public function findUser(string $hash = null)
	{
        if (!$hash)
        {
            $hash = $this->getSessionHash();
        }
        
        $session = $this->findSessionByHash($hash);
        if (!$session)
		{
            return null;
        }
        
        $user = new User($this->db, $session->userID);
        $user->read();
        return $user;
	}
	
    public function deleteUser(User $user, User $currUser, string $currUserPassword)
    {
        $return['error'] = true;
		
        if ($this->isBlocked())
		{
			$return['message'] = $this->strings["user_blocked"];
			return $return;
		}
        
        if (!password_verify($currUserPassword, $currUser->password))
		{
            $this->addAttempt();
            $return['message'] = $this->strings["password_incorrect"];
            return $return;
        }
        
        $user->isDeleted = true;
        $user->write();
        
        $this->deleteSessions($user->id);
        $this->deleteRequests($user);
		
        $return['error'] = false;
        $return['message'] = $this->strings["account_deleted"];
        return $return;
    }
	
    public function undeleteUser(User $user)
    {
        $return['error'] = true;
		
        if ($this->isBlocked())
		{
			$return['message'] = $this->strings["user_blocked"];
			return $return;
		}
		
		if (!$user->isDeleted)
		{
			$return['message'] = $this->strings["user_not_deleted"];
			return $return;
		}
        
		if ($this->config->email)
		{
            $user->password = $this->createHash($this->getRandomKey());
            $user->isDeleted = false;
            $user->isActive = false;
            $user->resetNeeded = true;
            
			$addRequest = $this->addRequest($user, ["reset", "activation"]);
			if ($addRequest['error'])
			{
				$this->addAttempt();
				$return['message'] = $addRequest['message'];
				return $return;
			}
		}
		else
		{
            $user->isDeleted = false;
        }
        $user->write();
		
        $return['error'] = false;
        $return['message'] = $this->strings["account_undeleted"];
        return $return;
    }
    
    private function getRequests(User $user, string $type)
    {
        $query = "SELECT id FROM requests WHERE userID = ? AND type = ?";
        $rows = $this->db->select($query, 'is', [&$user->id, &$type]);
        
        $requests = [];
        foreach ($rows as $row)
        {
            $request = new Requests($this->db, $row['id']);
            $request->read();
            $requests []= $request;
        }
        
        return $requests;
    }
    
    private function addRequest(User $user, array $types)
    {
        $return['error'] = true;
		
		if (!$this->config->email)
		{
            $return['message'] = $this->strings['function_disabled'];
            return $return;
        }
		
		foreach ($types as $type)
		{
            $requests = $this->getRequests($user, $type);
            foreach ($requests as $request)
			{
				if ($request->expire > time())
				{
					$return['message'] = $this->strings[$type . "_exists"];
					return $return;
				}
                
				$request->delete();
			}
		}
        
		if (in_array("activation", $types) && $user->isActive)
        {
            $return['message'] = $this->strings["already_activated"];
            return $return;
        }
        
        $emailKey = $this->getRandomKey(20);
        $expire = strtotime($this->config->request_key_expiration);
        
        $smsKey;
        if ($this->config->sms)
        {
            $smsKey = $this->getRandomKey(20);
        }
        
        $request_ids = [];
        foreach ($types as $type)
        {
            $request = new Requests($this->db);
            
            $request->userID = $user->id;
            $request->emailKey = $emailKey;
            $request->expire = $expire;
            $request->type = $type;
            
            if ($this->config->sms)
    		{
                $request->smsKey = $smsKey;
            }
            
            $request->write();
            
            $request_ids []= $request->id;
        }
        
        $subject;
        $body;
        $altBody;
        $siteURL = $this->config->site_url;
        if (!$siteURL)
        {
            $siteURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'];
        }
        if (in_array("activation", $types))
		{
			$subject = sprintf($this->strings['email_activation_subject'], $this->config->site_name);
			$body = sprintf($this->strings['email_activation_body'], $siteURL, $this->config->site_activation_page, $emailKey);
			$altBody = sprintf($this->strings['email_activation_altbody'], $siteURL, $this->config->site_activation_page, $emailKey);
		}
		else
		{
			$subject = sprintf($this->strings['email_reset_subject'], $this->config->site_name);
			$body = sprintf($this->strings['email_reset_body'], $siteURL, $this->config->site_password_reset_page, $emailKey);
			$altBody = sprintf($this->strings['email_reset_altbody'], $siteURL, $this->config->site_password_reset_page, $emailKey);
		}
        
		if (!$this->emailDelegate->sendMail($this->config, $user->email, $subject, $body, $altBody))
		{
            foreach ($request_ids as $request_id)
			{
                $request = new Requests($this->db, $request_id);
                $request->delete();
			}
            
			$return['message'] = $this->strings["email_error"];
			return $return;
		}
		
		if ($this->config->sms)
		{
			$body;
			if (in_array("activation", $types))
			{
				$body = sprintf($this->strings['sms_activation_body'], $this->config->site_name, $smsKey);
			}
			else
			{
				$body = sprintf($this->strings['sms_reset_body'], $this->config->site_name, $smsKey);
			}
			
			if (!$this->smsDelegate->sendSMS($user->phone, $body))
			{
				$return['message'] = $this->strings["sms_error"];
				return $return;
			}
		}
		
        $return['error'] = false;
        return $return;
    }
	
    private function getRequest(string $emailKey, string $smsKey = null, string $type, Requests &$request = null, bool $allowExpired = false)
    {
        $return['error'] = true;
        $return['expired'] = false;
        
        if (!$this->config->email)
		{
            $return['message'] = $this->strings['function_disabled'];
            return $return;
        }
        
        if (strlen($emailKey) !== 20)
		{
            $this->addAttempt();
            $return['message'] = $this->strings[$type . "key_invalid"]; 
            return $return;
        }
        
        if ($this->config->sms && strlen($smsKey) !== 20)
		{
            $return['message'] = $this->strings['sms_key_invalid'];
            return $return;
        }
		
        $query = "SELECT id FROM requests WHERE emailKey = ? AND type = ?";
        
        $types = 'ss';
        $params = [&$emailKey, &$type];
        if ($this->config->sms)
		{
            $query .= ' and smsKey = ?';
            $types .= 's';
            $params []= &$smsKey;
		}
        $rows = $this->db->select($query, $types, $params);
		
        if (count($rows) === 0)
		{
			$this->addAttempt();
            $return['message'] = $this->strings[$type . "key_incorrect"];
            return $return;
        }
		
        $row = $rows[0];
        
        $request = new Requests($this->db, $row['id']);
        $request->read();
		
        if ($request->expire < time())
		{
			$this->addAttempt();
            $return['message'] = $this->strings[$type . "key_expired"];
            $return['expired'] = true;
            if (!$allowExpired)
            {
                return $return;
            }
        }
		
        $return['error'] = false;
        return $return;
    }
	
    public function deleteRelatedRequests(string $idStr)
    {
		$ids = explode(",", $idStr);
		foreach ($ids as $id)
		{
            $request = new Requests($this->db, $id);
            if (!$request)
            {
                return false;
            }
            
			$request->delete();
		}
		
		return true;
    }
	
    private function deleteRequests(User $user)
    {
        $query = "select id from requests WHERE userID = ?";
        $rows = $this->db->select($query, 'i', [&$user->id]);
        
        foreach ($rows as $row)
        {
            $request = new Requests($this->db, $row['id']);
            $request->delete();    
        }
    }
	
    private function validatePassword(string $password)
	{
        $return['error'] = true;
		
        if (strlen($password) < $this->config->verify_password_min_length)
		{
            $return['message'] = $this->strings["password_short"];
            return $return;
        }
		
        $return['error'] = false;
        return $return;
    }
	
    private function validateEmail(string $email)
	{
        $return['error'] = true;
		
        if (strlen($email) < $this->config->verify_email_min_length)
		{
            $return['message'] = $this->strings["email_short"];
            return $return;
        }
		elseif (strlen($email) > $this->config->verify_email_max_length)
		{
            $return['message'] = $this->strings["email_long"];
            return $return;
        }
		elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
            $return['message'] = $this->strings["email_invalid"];
            return $return;
        }
		
        if ($this->config->verify_email_use_banlist)
		{
            $bannedEmails = json_decode(file_get_contents(__DIR__ . "/files/domains.json"));
            if (in_array(strtolower(explode('@', $email)[1]), $bannedEmails))
			{
                $return['message'] = $this->strings["email_banned"];
                return $return;
            }
        }
		
        $return['error'] = false;
        return $return;
    }
	
    public function changePassword(User &$user, string $currpass, string $newpass, string $repeatnewpass)
    {
        $return['error'] = true;
        
		if ($this->isBlocked())
		{
			$return['message'] = $this->strings["user_blocked"];
			return $return;
        }
		
        $validatePassword = $this->validatePassword($newpass);
        if ($validatePassword['error'])
		{
            $return['message'] = $validatePassword['message'];
            return $return;
        }
		
		if ($newpass !== $repeatnewpass)
		{
            $return['message'] = $this->strings["newpassword_nomatch"];
            return $return;
        }
		
        if (!password_verify($currpass, $user->password))
		{
            $this->addAttempt();
            $return['message'] = $this->strings["password_incorrect"];
            return $return;
        }
        
        if (password_verify($newpass, $user->password))
		{
            $this->addAttempt();
            $return['message'] = $this->strings["newpassword_match"];
            return $return;
        }
		
        $user->password = $this->createHash($newpass);
		$user->write();
		
        $return['error'] = false;
        $return['message'] = $this->strings["password_changed"];
        return $return;
    }
    
    public function changeEmail(User &$user, string $email)
    {
        return $this->changeEmail_impl($user, $email);
    }
    
    public function changeEmailUser(User &$user, string $email, string $password)
    {
        if (!$password)
        {
            $return['error'] = true;
            $return['message'] = $this->strings["password_must_be_supplied"];
			return $return;
        }
        
        return $this->changeEmail_impl($user, $email, $password);
    }
    
    private function changeEmail_impl(User &$user, string $email, string $password = NULL)
    {
        $return['error'] = true;
        
		if ($this->isBlocked())
		{
			$return['message'] = $this->strings["user_blocked"];
			return $return;
		}
        
        $validateEmail = $this->validateEmail($email);
        if ($validateEmail['error'])
		{
            $return['message'] = $validateEmail['message'];
            return $return;
        }
        
		if ($password)
		{
			if (!password_verify($password, $user->password))
			{
				$this->addAttempt();
				$return['message'] = $this->strings["password_incorrect"];
				return $return;
			}
		}
		
        if ($email == $user->email)
		{
            $this->addAttempt();
            $return['message'] = $this->strings["newemail_match"];
            return $return;
        }
        
        if (User::nameExists($email))
		{
            $this->addAttempt();
            $return['message'] = $this->strings["email_taken"];
            return $return;
        }
        
        $user->email = $email;
        $user->write();
		
        $return['error'] = false;
        $return['message'] = $this->strings["email_changed"];
        return $return;
    }
    
    public function isBlocked()
    {
        $ip = $this->getIp();
        
		$this->deleteAttempts($ip, false);
        
        $query = "SELECT count(*) as count FROM attempts WHERE ip = ?";
        $attempts = $this->db->select($query, 's', [&$ip])[0]['count'];
        
        return $attempts >= $this->config->attempts_before_ban;
    }
	
    private function addAttempt()
    {
        $attempt = new Attempt($this->db);
        $attempt->ip = $this->getIp();
        $attempt->expire = strtotime($this->config->attack_mitigation_time);
        $attempt->write();
    }
	
    private function deleteAttempts(string $ip, bool $all = false)
    {
        if ($all)
		{
            $query = "DELETE FROM attempts WHERE ip = ?";
            return $this->db->query($query, 's', [&$ip]);
        }
		else
		{
            $query = "SELECT id FROM attempts WHERE ip = ?";
			$rows = $this->db->select($query, 's', [&$ip]);
			
			foreach ($rows as $row)
			{
                $attempt = new Attempt($this->db, $row['id']);
                $attempt->read();
				if ($attempt->expire < time())
				{
                    $attempt->delete();
				}
			}
		}
    }
	
    private function getRandomKey(int $length = 20)
    {
        $chars = "A1B2C3D4E5F6G7H8I9J0K1L2M3N4O5P6Q7R8S9T0U1V2W3X4Y5Z6a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6";
        $key = "";
		
        for ($i = 0; $i < $length; $i++)
		{
            $key .= $chars{mt_rand(0, strlen($chars) - 1)};
        }
		
        return $key;
    }
    
    // Check whether a session is valid:
    //   return: 0 = valid, 1 = invalid-general, 2 = invalid-expired
    public function checkSession(string $hash)
    {
        if ($this->isBlocked())
		{
			return 1;
        }
        
        if (!isset($hash) || strlen($hash) != 40)
		{
            return 1;
        }
        
        $session = $this->findSessionByHash($hash);
        if (!$session)
		{
            return 1;
        }
        
        if ($session->expire < time())
		{
            $this->deleteSessions($session->userID);
            return 2;
        }
		
        if ($session->ip != $this->getIp())
		{
            return 1;
        }
		
        return 0;
    }
    
    // Returns whether the current request represents a logged in user:
    //   return: 0 = valid, 1 = invalid-general, 2 = invalid-expired
    public function isLogged()
	{
        return $this->checkSession($this->getSessionHash());
    }
	
    public function getSessionHash()
	{
        return $this->cookies->getCookie('authID');
    }
	
    public function comparePassword(User $user, string $password)
    {
        if (!$user)
		{
            return false;
        }
        
        return password_verify($password, $user->password);
    }
    
    private function getIp()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
           return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
		else
		{
           return $_SERVER['REMOTE_ADDR'];
        }
    }
}

interface EmailDelegate_i
{
    public function sendMail($config, $destination, $subject, $body, $altBody);
}

interface SMSDelegate_i
{
    public function sendSMS($phone, $body);
}

interface CookieDelegate_i
{
    public function setCookie($name, $value = "",  $expire = 0,  $path = "", $domain = "", $secure = false, $httponly = false);
    public function getCookie($name);
}

class CookieDelegate implements CookieDelegate_i
{
    public function setCookie($name, $value = "",  $expire = 0,  $path = "", $domain = "", $secure = false, $httponly = false)
    {
      return setcookie($name, $value,  $expire, $path, $domain, $secure, $httponly);
    }
    
    public function getCookie($name)
    {
        return $_COOKIE[$name];
    }
}

?>