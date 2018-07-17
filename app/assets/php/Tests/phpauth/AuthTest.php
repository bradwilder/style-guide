<?php

use PHPUnit\Framework\TestCase;

if (!defined('__SITE_PATH'))
{
	$site_path = realpath(__DIR__ . '/../../../..');
	define ('__SITE_PATH', $site_path);
	define ('__ASSETS_PATH', __SITE_PATH . '/assets');
}

require_once __ASSETS_PATH . '/php/DB/Db.php';
require_once __ASSETS_PATH . '/php/phpauth/Config.php';
require_once __ASSETS_PATH . '/php/phpauth/Auth.php';
require_once __ASSETS_PATH . '/php/Framework/Auth/EmailDelegate.php';
require_once __ASSETS_PATH . '/php/Framework/Auth/SMSDelegate.php';
require_once __ASSETS_PATH . '/php/Model/UserFactory.class.php';

require_once __ASSETS_PATH . '/php/DB/DataClassBase.php';
require_once __ASSETS_PATH . '/php/DataClasses/User.class.php';
require_once __ASSETS_PATH . '/php/DataClasses/Session.class.php';
require_once __ASSETS_PATH . '/php/DataClasses/Requests.class.php';
require_once __ASSETS_PATH . '/php/DataClasses/Attempt.class.php';

use PHPAuth\Auth;
use PHPAuth\CookieDelegate_i;

final class AuthTest extends TestCase
{
	private $db;
	private $origAddr;
	private $config;
	private $emailMock;
	private $smsMock;
	private $auth;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->db = new Db();
		$this->db->changeDatabase('test');
		
		$this->origAddr = $_SERVER['REMOTE_ADDR'];
		
		$this->config = new ConfigMock();
		
		$this->emailMock = $this->getMockBuilder(PHPAuth\EmailDelegate::class)
			->setMethods(['sendMail'])
			->getMock();
		$this->emailMock->method('sendMail')
			->willReturn(true);
		
		$this->smsMock = $this->getMockBuilder(PHPAuth\SMSDelegate::class)
			->setMethods(['sendSMS'])
			->getMock();
		$this->smsMock->method('sendSMS')
			->willReturn(true);
	}
	
	/**
     * @before
	 * @after
     */
	public function clear()
	{
		$this->db->query('delete from attempts');
		$this->db->query('delete from users');
		$this->db->query('delete from sessions');
		$this->db->query('delete from requests');
		
		$_SERVER['REMOTE_ADDR'] = $this->origAddr;
	}
	
	public function testRegister_Happy()
	{
		$user = $this->register(false);
		$this->assertEquals('destination@something.com', $user['email']);
		$this->assertEquals('+12083381119', $user['phone']);
		$this->assertEquals(0, $user['isActive']);
		$this->assertEquals(0, $user['isDeleted']);
		$this->assertEquals(1, $user['resetNeeded']);
		
		$this->assertEquals(2, $this->requestTableCount());
		$requests = $this->getRequests($user['id']);
		$this->assertEquals(2, count($requests));
		$this->assertNotNull($requests[0]['emailKey']);
		$this->assertNotNull($requests[0]['smsKey']);
		$this->assertNotNull($requests[0]['expire']);
		$this->assertEquals($requests[0]['emailKey'], $requests[1]['emailKey']);
		$this->assertEquals($requests[0]['smsKey'], $requests[1]['smsKey']);
		$this->assertEquals($requests[0]['expire'], $requests[1]['expire']);
		$this->assertEquals(1, count(array_filter($requests, function($request) {return $request['type'] == 'activation';})));
		$this->assertEquals(1, count(array_filter($requests, function($request) {return $request['type'] == 'reset';})));
	}
	
	public function testRegister_HappyNoSMS()
	{
		$user = $this->register(false, true, false);
		$this->assertEquals('destination@something.com', $user['email']);
		$this->assertEquals('+12083381119', $user['phone']);
		$this->assertEquals(0, $user['isActive']);
		$this->assertEquals(0, $user['isDeleted']);
		$this->assertEquals(1, $user['resetNeeded']);
		
		$this->assertEquals(2, $this->requestTableCount());
		$requests = $this->getRequests($user['id']);
		$this->assertEquals(2, count($requests));
		$this->assertNotNull($requests[0]['emailKey']);
		$this->assertNull($requests[0]['smsKey']);
		$this->assertNotNull($requests[0]['expire']);
		$this->assertEquals($requests[0]['emailKey'], $requests[1]['emailKey']);
		$this->assertEquals($requests[0]['smsKey'], $requests[1]['smsKey']);
		$this->assertEquals($requests[0]['expire'], $requests[1]['expire']);
		$this->assertEquals(1, count(array_filter($requests, function($request) {return $request['type'] == 'activation';})));
		$this->assertEquals(1, count(array_filter($requests, function($request) {return $request['type'] == 'reset';})));
	}
	
	public function testRegister_HappyNoSMSPasswords()
	{
		$user = $this->register(true, true, false);
		$this->assertEquals('destination@something.com', $user['email']);
		$this->assertEquals('+12083381119', $user['phone']);
		$this->assertEquals(0, $user['isActive']);
		$this->assertEquals(0, $user['isDeleted']);
		$this->assertEquals(0, $user['resetNeeded']);
		
		$this->assertEquals(1, $this->requestTableCount());
		$requests = $this->getRequests($user['id']);
		$this->assertEquals(1, count($requests));
		$this->assertNotNull($requests[0]['emailKey']);
		$this->assertNull($requests[0]['smsKey']);
		$this->assertNotNull($requests[0]['expire']);
		$this->assertEquals('activation', $requests[0]['type']);
	}
	
	public function testRegister_HappyNoEmail()
	{
		$user = $this->register(true, false);
		$this->assertEquals('destination@something.com', $user['email']);
		$this->assertEquals('+12083381119', $user['phone']);
		$this->assertEquals(1, $user['isActive']);
		$this->assertEquals(0, $user['isDeleted']);
		$this->assertEquals(0, $user['resetNeeded']);
		
		$this->assertEquals(0, $this->requestTableCount());
	}
	
	public function testRegister_HappyPasswords()
	{
		$user = $this->register();
		$this->assertEquals('destination@something.com', $user['email']);
		$this->assertEquals('+12083381119', $user['phone']);
		$this->assertEquals(0, $user['isActive']);
		$this->assertEquals(0, $user['isDeleted']);
		$this->assertEquals(0, $user['resetNeeded']);
		
		$this->assertEquals(1, $this->requestTableCount());
		$requests = $this->getRequests($user['id']);
		$this->assertEquals(1, count($requests));
		$this->assertNotNull($requests[0]['emailKey']);
		$this->assertNotNull($requests[0]['smsKey']);
		$this->assertNotNull($requests[0]['expire']);
		$this->assertEquals('activation', $requests[0]['type']);
	}
	
	public function testRegister_PasswordMismatch()
	{
		$this->auth = new Auth($this->db, $this->config, $this->emailMock, $this->smsMock, new CookiesMock());
		
		$return = $this->auth->register('destination@something.com', '+12083381119', 'test', 'test2');
		$this->assertTrue($return['error']);
	}
	
	public function testRegister_NoRepeatPassword()
	{
		$this->auth = new Auth($this->db, $this->config, $this->emailMock, $this->smsMock, new CookiesMock());
		
		$return = $this->auth->register('destination@something.com', '+12083381119', 'test');
		$this->assertTrue($return['error']);
	}
	
	public function testRegister_NoEmailNoPassword()
	{
		$this->config->email = false;
		$this->auth = new Auth($this->db, $this->config, $this->emailMock, $this->smsMock, new CookiesMock());
		
		$return = $this->auth->register('destination@something.com', '+12083381119');
		$this->assertTrue($return['error']);
	}
	
	public function testRegister_DuplicateEmail()
	{
		$this->auth = new Auth($this->db, $this->config, $this->emailMock, $this->smsMock, new CookiesMock());
		
		$return = $this->auth->register('destination@something.com', '+12083381119');
		$this->assertFalse($return['error']);
		
		$return = $this->auth->register('destination@something.com', '+12083381119');
		$this->assertTrue($return['error']);
	}
	
	public function testRegister_InvalidEmail()
	{
		$this->auth = new Auth($this->db, $this->config, $this->emailMock, $this->smsMock, new CookiesMock());
		
		$return = $this->auth->register('invalidEmail#%$#%$#%$#', '+12083381119');
		$this->assertTrue($return['error']);
	}
	
	public function testLogin_Happy()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
	}
	
	public function testLogin_NoUser()
	{
		$this->auth = new Auth($this->db, $this->config, $this->emailMock, $this->smsMock, new CookiesMock());
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertTrue($return['error']);
	}
	
	public function testLogin_UserDeleted()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$return = $this->auth->deleteUser($user, $user, 'test');
		$this->assertFalse($return['error']);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertTrue($return['error']);
	}
	
	public function testLogin_UserInactive()
	{
		$this->register();
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertTrue($return['error']);
	}
	
	public function testLogin_UserResetNeeded()
	{
		$this->register(false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertTrue($return['error']);
	}
	
	public function testLogin_WrongPassword()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'testtube');
		$this->assertTrue($return['error']);
	}
	
	public function testLogout_Happy()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		
		$return = $this->auth->logout();
		$this->assertTrue($return);
	}
	
	public function testLogout_HappyHash()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		
		$return = $this->auth->logout($return['hash']);
		$this->assertTrue($return);
	}
	
	public function testLogout_NoHashMatch()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		
		$return = $this->auth->logout('some garbage hash');
		$this->assertFalse($return);
	}
	
	public function testResendActivation_Happy()
	{
		$userRow = $this->register(false, true, true, 2);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$this->assertEquals(2, $this->requestTableCount());
		
		$this->db->query('update requests set expire = (NOW() - 100000)');
		
		$return = $this->auth->resendActivation($user, true);
		$this->assertFalse($return['error']);
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertTrue($user->resetNeeded);
		
		$this->assertEquals(2, $this->requestTableCount());
		$requests = $this->getRequests($user->id);
		$this->assertEquals(2, count($requests));
		$this->assertNotNull($requests[0]['emailKey']);
		$this->assertNotNull($requests[0]['smsKey']);
		$this->assertNotNull($requests[0]['expire']);
		$this->assertEquals($requests[0]['emailKey'], $requests[1]['emailKey']);
		$this->assertEquals($requests[0]['smsKey'], $requests[1]['smsKey']);
		$this->assertEquals($requests[0]['expire'], $requests[1]['expire']);
		$this->assertEquals(1, count(array_filter($requests, function($request) {return $request['type'] == 'activation';})));
		$this->assertEquals(1, count(array_filter($requests, function($request) {return $request['type'] == 'reset';})));
	}
	
	public function testResendActivation_HappyNoReset()
	{
		$userRow = $this->register(true, true, true, 2);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$this->assertEquals(1, $this->requestTableCount());
		
		$this->db->query('update requests set expire = (NOW() - 100000)');
		
		$return = $this->auth->resendActivation($user, false);
		$this->assertFalse($return['error']);
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertFalse($user->resetNeeded);
		
		$this->assertEquals(1, $this->requestTableCount());
		$requests = $this->getRequests($user->id);
		$this->assertEquals(1, count($requests));
		$this->assertNotNull($requests[0]['emailKey']);
		$this->assertNotNull($requests[0]['smsKey']);
		$this->assertNotNull($requests[0]['expire']);
		$this->assertEquals('activation', $requests[0]['type']);
	}
	
	public function testResendActivation_AlreadyActive()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$return = $this->auth->resendActivation($user, true);
		$this->assertTrue($return['error']);
	}
	
	public function testResendActivation_NotExpired()
	{
		$userRow = $this->register(false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$return = $this->auth->resendActivation($user, true);
		$this->assertTrue($return['error']);
	}
	
	public function testResendActivationUser_Happy()
	{
		$userRow = $this->register(false, true, true, 2);
		
		$this->db->query('update requests set expire = (NOW() - 100000)');
		
		$requests = $this->getRequests($userRow['id']);
		
		$return = $this->auth->resendActivationUser($requests[0]['emailKey'], $requests[0]['smsKey']);
		$this->assertFalse($return['error']);
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertTrue($user->resetNeeded);
		
		$this->assertEquals(2, $this->requestTableCount());
		$requests = $this->getRequests($user->id);
		$this->assertEquals(2, count($requests));
		$this->assertNotNull($requests[0]['emailKey']);
		$this->assertNotNull($requests[0]['smsKey']);
		$this->assertNotNull($requests[0]['expire']);
		$this->assertEquals($requests[0]['emailKey'], $requests[1]['emailKey']);
		$this->assertEquals($requests[0]['smsKey'], $requests[1]['smsKey']);
		$this->assertEquals($requests[0]['expire'], $requests[1]['expire']);
		$this->assertEquals(1, count(array_filter($requests, function($request) {return $request['type'] == 'activation';})));
		$this->assertEquals(1, count(array_filter($requests, function($request) {return $request['type'] == 'reset';})));
	}
	
	public function testResendActivationUser_HappyNoReset()
	{
		$userRow = $this->register(true, true, true, 2);
		
		$this->db->query('update requests set expire = (NOW() - 100000)');
		
		$requests = $this->getRequests($userRow['id']);
		
		$return = $this->auth->resendActivationUser($requests[0]['emailKey'], $requests[0]['smsKey']);
		$this->assertFalse($return['error']);
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertFalse($user->resetNeeded);
		
		$this->assertEquals(1, $this->requestTableCount());
		$requests = $this->getRequests($user->id);
		$this->assertEquals(1, count($requests));
		$this->assertNotNull($requests[0]['emailKey']);
		$this->assertNotNull($requests[0]['smsKey']);
		$this->assertNotNull($requests[0]['expire']);
		$this->assertEquals('activation', $requests[0]['type']);
	}
	
	public function testResendActivationUser_HappyNoSMS()
	{
		$userRow = $this->register(false, true, false, 2);
		
		$this->db->query('update requests set expire = (NOW() - 100000)');
		
		$requests = $this->getRequests($userRow['id']);
		
		$return = $this->auth->resendActivationUser($requests[0]['emailKey']);
		$this->assertFalse($return['error']);
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertTrue($user->resetNeeded);
		$this->assertEquals(2, $this->requestTableCount());
		$requests = $this->getRequests($user->id);
		$this->assertEquals(2, count($requests));
		$this->assertNotNull($requests[0]['emailKey']);
		$this->assertNull($requests[0]['smsKey']);
		$this->assertNotNull($requests[0]['expire']);
		$this->assertEquals($requests[0]['emailKey'], $requests[1]['emailKey']);
		$this->assertEquals($requests[0]['smsKey'], $requests[1]['smsKey']);
		$this->assertEquals($requests[0]['expire'], $requests[1]['expire']);
		$this->assertEquals(1, count(array_filter($requests, function($request) {return $request['type'] == 'activation';})));
		$this->assertEquals(1, count(array_filter($requests, function($request) {return $request['type'] == 'reset';})));
	}
	
	public function testResendActivationUser_NotExpired()
	{
		$userRow = $this->register(false);
		
		$requests = $this->getRequests($userRow['id']);
		
		$return = $this->auth->resendActivationUser($requests[0]['emailKey'], $requests[0]['smsKey']);
		$this->assertTrue($return['error']);
	}
	
	public function testResendActivationUser_WrongKey()
	{
		$userRow = $this->register(false);
		
		$this->db->query('update requests set expire = (NOW() - 100000)');
		
		$requests = $this->getRequests($userRow['id']);
		
		$return = $this->auth->resendActivationUser($requests[0]['smsKey'], $requests[0]['emailKey']);
		$this->assertTrue($return['error']);
	}
	
	public function testRequestReset_Happy()
	{
		$user = $this->activate(true, true, true, 2);
		
		$return = $this->auth->requestReset('destination@something.com');
		$this->assertFalse($return['error']);
		$this->assertEquals(1, $this->requestTableCount());
		$requests = $this->getRequests($user['id']);
		$this->assertEquals(1, count($requests));
		$this->assertNotNull($requests[0]['emailKey']);
		$this->assertNotNull($requests[0]['smsKey']);
		$this->assertNotNull($requests[0]['expire']);
		$this->assertEquals('reset', $requests[0]['type']);
	}
	
	public function testRequestReset_HappyNoSMS()
	{
		$user = $this->activate(true, true, false, 2);
		
		$return = $this->auth->requestReset('destination@something.com');
		$this->assertFalse($return['error']);
		$this->assertEquals(1, $this->requestTableCount());
		$requests = $this->getRequests($user['id']);
		$this->assertEquals(1, count($requests));
		$this->assertNotNull($requests[0]['emailKey']);
		$this->assertNull($requests[0]['smsKey']);
		$this->assertNotNull($requests[0]['expire']);
		$this->assertEquals('reset', $requests[0]['type']);
	}
	
	public function testRequestReset_InvalidEmail()
	{
		$this->auth = new Auth($this->db, $this->config, $this->emailMock, $this->smsMock, new CookiesMock());
		
		$return = $this->auth->requestReset('invalidemail');
		$this->assertTrue($return['error']);
	}
	
	public function testRequestReset_WrongEmail()
	{
		$this->activate();
		
		$return = $this->auth->requestReset('newEmail@something.com');
		$this->assertTrue($return['error']);
	}
	
	public function testRequestReset_WrongEmailDisguise()
	{
		$this->config->email_send_disguise = 1;
		
		$this->activate(true, true, false);
		
		$return = $this->auth->requestReset('newEmail@something.com');
		$this->assertFalse($return['error']);
	}
	
	public function testRequestReset_ResetExists()
	{
		$this->activate(true, true, true, 2);
		
		$return = $this->auth->requestReset('destination@something.com');
		$this->assertFalse($return['error']);
		
		$return = $this->auth->requestReset('destination@something.com');
		$this->assertTrue($return['error']);
	}
	
	public function testActivate_Happy()
	{
		$user = $this->activate();
		
		$this->assertEquals(1, $user['isActive']);
	}
	
	public function testActivate_HappyNoSMS()
	{
		$user = $this->activate(true, true, false);
		
		$this->assertEquals(1, $user['isActive']);
	}
	
	public function testActivate_MissingKey()
	{
		$user = $this->register();
		
		$requests = $this->getRequests($user['id']);
		
		$return = $this->auth->activate($requests[0]['emailKey'], 'test');
		$this->assertTrue($return['error']);
	}
	
	public function testActivate_WrongKeys()
	{
		$this->register();
		
		$return = $this->auth->activate('1234567890123456789012345678901234567890', 'test', '1234567890123456789012345678901234567890');
		$this->assertTrue($return['error']);
	}
	
	public function testActivate_RequestExpired()
	{
		$user = $this->register();
		
		$this->db->query('update requests set expire = (NOW() - 100000)');
		
		$requests = $this->getRequests($user['id']);
		
		$return = $this->auth->activate($requests[0]['emailKey'], 'test', $requests[0]['smsKey']);
		$this->assertTrue($return['error']);
		$this->assertTrue($return['expired']);
	}
	
	public function testActivate_ResetNeeded()
	{
		$user = $this->register(false);
		
		$requests = $this->getRequests($user['id']);
		
		$return = $this->auth->activate($requests[0]['emailKey'], 'test', $requests[0]['smsKey']);
		$this->assertTrue($return['error']);
	}
	
	public function testActivate_UserDeleted()
	{
		$userRow = $this->register();
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$requests = $this->getRequests($user->id);
		
		$return = $this->auth->deleteUser($user, $user, 'test');
		$this->assertFalse($return['error']);
		
		$return = $this->auth->activate($requests[0]['emailKey'], 'test', $requests[0]['smsKey']);
		$this->assertTrue($return['error']);
	}
	
	public function testActivate_WrongPassword()
	{
		$user = $this->register();
		
		$requests = $this->getRequests($user['id']);
		
		$return = $this->auth->activate($requests[0]['emailKey'], 'testtube', $requests[0]['smsKey']);
		$this->assertTrue($return['error']);
	}
	
	public function testResetPassword_Happy()
	{
		$user = $this->activate(true, true, true, 2);
		
		$return = $this->auth->requestReset('destination@something.com');
		$this->assertFalse($return['error']);
		
		$requests = $this->getRequests($user['id']);
		$this->assertEquals(1, count($requests));
		
		$return = $this->auth->resetPassword($requests[0]['emailKey'], 'testtube', 'testtube', $requests[0]['smsKey']);
		$this->assertFalse($return['error']);
	}
	
	public function testResetPassword_HappyNoSMS()
	{
		$user = $this->activate(true, true, false, 2);
		
		$return = $this->auth->requestReset('destination@something.com');
		$this->assertFalse($return['error']);
		
		$requests = $this->getRequests($user['id']);
		$this->assertEquals(1, count($requests));
		
		$return = $this->auth->resetPassword($requests[0]['emailKey'], 'testtube', 'testtube');
		$this->assertFalse($return['error']);
	}
	
	public function testResetPassword_WrongKeys()
	{
		$user = $this->activate(true, true, true, 2);
		
		$return = $this->auth->requestReset('destination@something.com');
		$this->assertFalse($return['error']);
		
		$requests = $this->getRequests($user['id']);
		$this->assertEquals(1, count($requests));
		
		$return = $this->auth->resetPassword('1234567890123456789012345678901234567890', 'testtube', 'testtube', '1234567890123456789012345678901234567890');
		$this->assertTrue($return['error']);
	}
	
	public function testResetPassword_MissingKey()
	{
		$user = $this->activate(true, true, true, 2);
		
		$return = $this->auth->requestReset('destination@something.com');
		$this->assertFalse($return['error']);
		
		$requests = $this->getRequests($user['id']);
		$this->assertEquals(1, count($requests));
		
		$return = $this->auth->resetPassword($requests[0]['emailKey'], 'testtube', 'testtube');
		$this->assertTrue($return['error']);
	}
	
	public function testResetPassword_RequestExpired()
	{
		$user = $this->activate(true, true, true, 2);
		
		$return = $this->auth->requestReset('destination@something.com');
		$this->assertFalse($return['error']);
		
		$this->db->query('update requests set expire = (NOW() - 100000)');
		
		$requests = $this->getRequests($user['id']);
		$this->assertEquals(1, count($requests));
		
		$return = $this->auth->resetPassword($requests[0]['emailKey'], 'testtube', 'testtube', $requests[0]['smsKey']);
		$this->assertTrue($return['error']);
		$this->assertTrue($return['expired']);
	}
	
	public function testResetPassword_PasswordInvalid()
	{
		$user = $this->activate(true, true, true, 2);
		
		$return = $this->auth->requestReset('destination@something.com');
		$this->assertFalse($return['error']);
		
		$requests = $this->getRequests($user['id']);
		$this->assertEquals(1, count($requests));
		
		$newPass = '';
		for ($i = 0; $i < ($this->config->verify_password_min_length - 1); $i++)
		{
			$newPass .= 'a';
		}
		
		$return = $this->auth->resetPassword($requests[0]['emailKey'], $newPass, $newPass);
		$this->assertTrue($return['error']);
	}
	
	public function testResetPassword_PasswordMismatch()
	{
		$user = $this->activate(true, true, true, 2);
		
		$return = $this->auth->requestReset('destination@something.com');
		$this->assertFalse($return['error']);
		
		$requests = $this->getRequests($user['id']);
		$this->assertEquals(1, count($requests));
		
		$return = $this->auth->resetPassword($requests[0]['emailKey'], 'testtube', 'testdrive', $requests[0]['smsKey']);
		$this->assertTrue($return['error']);
	}
	
	public function testResetPassword_PasswordSame()
	{
		$user = $this->activate(true, true, true, 2);
		
		$return = $this->auth->requestReset('destination@something.com');
		$this->assertFalse($return['error']);
		
		$requests = $this->getRequests($user['id']);
		
		$return = $this->auth->resetPassword($requests[0]['emailKey'], 'test', 'test', $requests[0]['smsKey']);
		$this->assertTrue($return['error']);
	}
	
	public function testResetActivate_Happy()
	{
		$user = $this->register(false);
		
		$requests = $this->getRequests($user['id']);
		
		$this->assertEquals(0, $user['isActive']);
		$this->assertEquals(1, $user['resetNeeded']);
		
		$return = $this->auth->resetActivate($requests[0]['emailKey'], 'test', 'test', $requests[0]['smsKey']);
		$this->assertFalse($return['error']);
		$user = $this->getNewUser();
		$this->assertEquals(1, $user['isActive']);
		$this->assertEquals(0, $user['resetNeeded']);
	}
	
	public function testResetActivate_HappyNoSMS()
	{
		$user = $this->register(false, true, false);
		
		$requests = $this->getRequests($user['id']);
		
		$this->assertEquals(0, $user['isActive']);
		$this->assertEquals(1, $user['resetNeeded']);
		
		$return = $this->auth->resetActivate($requests[0]['emailKey'], 'test', 'test');
		$this->assertFalse($return['error']);
		$user = $this->getNewUser();
		$this->assertEquals(1, $user['isActive']);
		$this->assertEquals(0, $user['resetNeeded']);
	}
	
	public function testResetActivate_MissingKey()
	{
		$user = $this->register(false);
		
		$requests = $this->getRequests($user['id']);
		
		$return = $this->auth->resetActivate($requests[0]['emailKey'], 'test', 'test');
		$this->assertTrue($return['error']);
	}
	
	public function testResetActivate_WrongKeys()
	{
		$user = $this->register(false);
		
		$requests = $this->getRequests($user['id']);
		
		$return = $this->auth->resetActivate('1234567890123456789012345678901234567890', 'test', 'test', '1234567890123456789012345678901234567890');
		$this->assertTrue($return['error']);
	}
	
	public function testResetActivate_RequestExpired()
	{
		$user = $this->register(false);
		
		$this->db->query('update requests set expire = (NOW() - 100000)');
		
		$requests = $this->getRequests($user['id']);
		
		$return = $this->auth->resetActivate($requests[0]['emailKey'], 'test', 'test', $requests[0]['smsKey']);
		$this->assertTrue($return['error']);
		$this->assertTrue($return['expired']);
	}
	
	public function testResetActivate_NoResetNeeded()
	{
		$user = $this->register();
		
		$requests = $this->getRequests($user['id']);
		
		$return = $this->auth->resetActivate($requests[0]['emailKey'], 'test', 'test', $requests[0]['smsKey']);
		$this->assertTrue($return['error']);
	}
	
	public function testResetActivate_PasswordInvalid()
	{
		$user = $this->register(false);
		
		$requests = $this->getRequests($user['id']);
		
		$newPass = '';
		for ($i = 0; $i < ($this->config->verify_password_min_length - 1); $i++)
		{
			$newPass .= 'a';
		}
		
		$return = $this->auth->resetActivate($requests[0]['emailKey'], $newPass, $newPass, $requests[0]['smsKey']);
		$this->assertTrue($return['error']);
	}
	
	public function testResetActivate_PasswordMismatch()
	{
		$user = $this->register(false);
		
		$requests = $this->getRequests($user['id']);
		
		$newPass = '';
		for ($i = 0; $i < ($this->config->verify_password_min_length - 1); $i++)
		{
			$newPass .= 'a';
		}
		
		$return = $this->auth->resetActivate($requests[0]['emailKey'], 'testtube', 'testdrive', $requests[0]['smsKey']);
		$this->assertTrue($return['error']);
	}
	
	public function testResetActivate_UserDeleted()
	{
		$user = $this->register(false);
		
		$query = 'update users set isDeleted = 1 where id = ' . $user['id'];
		$this->db->query($query);
		
		$requests = $this->getRequests($user['id']);
		
		$return = $this->auth->resetActivate($requests[0]['emailKey'], 'test', 'test', $requests[0]['smsKey']);
		$this->assertTrue($return['error']);
	}
	
	public function testFindUser_Happy()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		$hash = $return['hash'];
		
		$user = $this->auth->findUser($hash);
		$this->assertEquals('destination@something.com', $user->email);
		$this->assertEquals('+12083381119', $user->phone);
	}
	
	public function testFindUser_WrongHash()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		
		$user = $this->auth->findUser('obviously wrong hash');
		$this->assertNull($user);
	}
	
	public function testDeleteUser_Happy()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertFalse($user->isDeleted);
		
		$return = $this->auth->deleteUser($user, $user, 'test');
		$this->assertFalse($return['error']);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertTrue($user->isDeleted);
	}
	
	public function testDeleteUser_InvalidPassword()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertFalse($user->isDeleted);
		
		$return = $this->auth->deleteUser($user, $user, 'testtube');
		$this->assertTrue($return['error']);
	}
	
	public function testUndeleteUser_Happy()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertFalse($user->isDeleted);
		
		$return = $this->auth->deleteUser($user, $user, 'test');
		$this->assertFalse($return['error']);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertTrue($user->isDeleted);
		
		$return = $this->auth->undeleteUser($user);
		$this->assertFalse($return['error']);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertFalse($user->isDeleted);
	}
	
	public function testUndeleteUser_HappyEmail()
	{
		$userRow = $this->activate(true, true, true, 2);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$return = $this->auth->deleteUser($user, $user, 'test');
		$this->assertFalse($return['error']);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$return = $this->auth->undeleteUser($user);
		$this->assertFalse($return['error']);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertFalse($user->isActive);
		$this->assertFalse($user->isDeleted);
		$this->assertTrue($user->resetNeeded);
		
		$this->assertEquals(2, $this->requestTableCount());
		$requests = $this->getRequests($user->id);
		$this->assertEquals(2, count($requests));
		$this->assertNotNull($requests[0]['emailKey']);
		$this->assertNotNull($requests[0]['smsKey']);
		$this->assertNotNull($requests[0]['expire']);
		$this->assertEquals($requests[0]['emailKey'], $requests[1]['emailKey']);
		$this->assertEquals($requests[0]['smsKey'], $requests[1]['smsKey']);
		$this->assertEquals($requests[0]['expire'], $requests[1]['expire']);
		$this->assertEquals(1, count(array_filter($requests, function($request) {return $request['type'] == 'activation';})));
		$this->assertEquals(1, count(array_filter($requests, function($request) {return $request['type'] == 'reset';})));
	}
	
	public function testUndeleteUser_WasntDeleted()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertFalse($user->isDeleted);
		
		$return = $this->auth->undeleteUser($user);
		$this->assertTrue($return['error']);
	}
	
	public function testDeleteRelatedRequests()
	{
		$this->auth = new Auth($this->db, $this->config, $this->emailMock, $this->smsMock, new CookiesMock());
		
		$insertedRequest1 = $this->insertRequestSimple();
		$this->assertNotNull($insertedRequest1);
		$insertedRequest2 = $this->insertRequestSimple();
		$this->assertNotNull($insertedRequest2);
		$insertedRequest3 = $this->insertRequestSimple();
		$this->assertNotNull($insertedRequest3);
		$this->assertEquals(3, $this->requestTableCount());
		
		$requestStr = $insertedRequest1 . ',' . $insertedRequest2 . ',' . $insertedRequest3;
		$return = $this->auth->deleteRelatedRequests($requestStr);
		$this->assertTrue($return);
		$this->assertEquals(0, $this->requestTableCount());
	}
	
	public function testChangePassword_Happy()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$return = $this->auth->changePassword($user, 'test', 'testtube', 'testtube');
		$this->assertFalse($return['error']);
	}
	
	public function testChangePassword_NewPasswordMismatch()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$return = $this->auth->changePassword($user, 'test', 'testtube', 'testdrive');
		$this->assertTrue($return['error']);
	}
	
	public function testChangePassword_NewPasswordInvalid()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$newPass = '';
		for ($i = 0; $i < ($this->config->verify_password_min_length - 1); $i++)
		{
			$newPass .= 'a';
		}
		
		$return = $this->auth->changePassword($user, 'test', $newPass, $newPass);
		$this->assertTrue($return['error']);
	}
	
	public function testChangePassword_NewPasswordSame()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$newPass = '';
		for ($i = 0; $i < ($this->config->verify_password_min_length - 1); $i++)
		{
			$newPass .= 'a';
		}
		
		$return = $this->auth->changePassword($user, 'test', 'test', 'test');
		$this->assertTrue($return['error']);
	}
	
	public function testChangePassword_OldPasswordInvalid()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$return = $this->auth->changePassword($user, 'testdrive', 'testtube', 'testtube');
		$this->assertTrue($return['error']);
	}
	
	public function testChangeEmail_Happy()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$return = $this->auth->changeEmail($user, 'brandnew@something.com');
		$this->assertFalse($return['error']);
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertEquals('brandnew@something.com', $user->email);
	}
	
	public function testChangeEmailUser_Happy()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$return = $this->auth->changeEmailUser($user, 'brandnew@something.com', 'test');
		$this->assertFalse($return['error']);
		$user = new User($this->db, $userRow['id']);
		$user->read();
		$this->assertEquals('brandnew@something.com', $user->email);
	}
	
	public function testChangeEmailUser_WrongPassword()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$return = $this->auth->changeEmailUser($user, 'brandnew@something.com', 'testtube');
		$this->assertTrue($return['error']);
	}
	
	public function testChangeEmailUser_InvalidEmail()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$return = $this->auth->changeEmailUser($user, 'something not an email', 'test');
		$this->assertTrue($return['error']);
	}
	
	public function testChangeEmailUser_SameEmail()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$return = $this->auth->changeEmailUser($user, 'destination@something.com', 'test');
		$this->assertTrue($return['error']);
	}
	
	public function testChangeEmailUser_Duplicate()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$return = $this->auth->register('secondPerson@something.com', '+12083381119', 'test', 'test');
		$this->assertFalse($return['error']);
		
		$return = $this->auth->changeEmailUser($user, 'secondPerson@something.com', 'test');
		$this->assertTrue($return['error']);
	}
	
	public function testIsBlocked_False()
	{
		$this->auth = new Auth($this->db, $this->config, $this->emailMock, $this->smsMock, new CookiesMock());
		
		$return = $this->auth->isBlocked();
		$this->assertFalse($return);
	}
	
	public function testIsBlocked_True()
	{
		$this->auth = new Auth($this->db, $this->config, $this->emailMock, $this->smsMock, new CookiesMock());
		
		for ($i = 0; $i < ($this->config->attempts_before_ban + 5); $i++)
		{
			$this->db->query('insert into attempts (ip, expire) values (?, (NOW() + 100000000))', 's', [&$_SERVER['REMOTE_ADDR']]);
		}
		$this->assertEquals($this->config->attempts_before_ban + 5, $this->attemptTableCount());
		
		$return = $this->auth->isBlocked();
		$this->assertTrue($return);
	}
	
	public function testCheckSession_Valid()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		
		$return = $this->auth->checkSession($return['hash']);
		$this->assertEquals(0, $return);
	}
	
	public function testCheckSession_WrongIP()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		
		$_SERVER['REMOTE_ADDR'] = '1.1.1.1';
		
		$return = $this->auth->checkSession($return['hash']);
		$this->assertEquals(1, $return);
	}
	
	public function testCheckSession_BadFormat()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		
		$return = $this->auth->checkSession('wrong format for hash');
		$this->assertEquals(1, $return);
	}
	
	public function testCheckSession_NoSession()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		
		$this->db->query('delete from sessions');
		
		$return = $this->auth->checkSession($return['hash']);
		$this->assertEquals(1, $return);
	}
	
	public function testCheckSession_SessionExpired()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		
		$this->db->query('update sessions set expire = (NOW() - 1000)');
		
		$return = $this->auth->checkSession($return['hash']);
		$this->assertEquals(2, $return);
	}
	
	public function testIsLogged_Valid()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		
		$return = $this->auth->isLogged();
		$this->assertEquals(0, $return);
	}
	
	public function testIsLogged_WrongIP()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		
		$_SERVER['REMOTE_ADDR'] = '1.1.1.1';
		
		$return = $this->auth->isLogged();
		$this->assertEquals(1, $return);
	}
	
	public function testIsLogged_NoSession()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		
		$this->db->query('delete from sessions');
		
		$return = $this->auth->isLogged();
		$this->assertEquals(1, $return);
	}
	
	public function testIsLogged_SessionExpired()
	{
		$this->register(true, false);
		
		$return = $this->auth->login('destination@something.com', 'test');
		$this->assertFalse($return['error']);
		
		$this->db->query('update sessions set expire = (NOW() - 1000)');
		
		$return = $this->auth->isLogged();
		$this->assertEquals(2, $return);
	}
	
	public function testComparePassword_Happy()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$return = $this->auth->comparePassword($user, 'test');
		$this->assertTrue($return);
	}
	
	public function testComparePassword_Mismatch()
	{
		$userRow = $this->register(true, false);
		
		$user = new User($this->db, $userRow['id']);
		$user->read();
		
		$return = $this->auth->comparePassword($user, 'testtube');
		$this->assertFalse($return);
	}
	
	private function register($withPasswords = true, $withEmail = true, $withSMS = true, $requestCount = 1)
	{
		if (!$withEmail)
		{
			$withSMS = false;
		}
		
		$this->config->email = $withEmail;
		$this->config->sms = $withSMS;
		
		$this->auth = new Auth($this->db, $this->config, $this->emailMock, $this->smsMock, new CookiesMock());
		
		$this->emailMock->expects($this->exactly($withEmail ? $requestCount : 0))
			->method('sendMail')
			->with
			(
				$this->equalTo($this->config),
				$this->equalTo('destination@something.com')
			);
		
		$this->smsMock->expects($this->exactly($withSMS ? $requestCount : 0))
			->method('sendSMS')
			->with($this->equalTo('+12083381119'));
		
		if ($withPasswords)
		{
			$return = $this->auth->register('destination@something.com', '+12083381119', 'test', 'test');
		}
		else
		{
			$return = $this->auth->register('destination@something.com', '+12083381119');
		}
		$this->assertFalse($return['error']);
		$this->assertEquals(1, $this->userTableCount());
		return $this->getNewUser();
	}
	
	public function activate($withPasswords = true, $withEmail = true, $withSMS = true, $requestCount = 1)
	{
		$userRow = $this->register($withPasswords, $withEmail, $withSMS, $requestCount);
		
		$requests = $this->getRequests($userRow['id']);
		
		$return = $this->auth->activate($requests[0]['emailKey'], 'test', $requests[0]['smsKey']);
		$this->assertFalse($return['error']);
		
		return $this->getNewUser();
	}
	
	private function insertRequestSimple()
	{
		$this->db->query('insert into requests values ()');
		return $this->db->insert_id();
	}
	
	private function userTableCount()
	{
		$query = 'select count(*) as count from users';
		$rows = $this->db->select($query);
		return $rows[0]['count'];
	}
	
	private function requestTableCount()
	{
		$query = 'select count(*) as count from requests';
		$rows = $this->db->select($query);
		return $rows[0]['count'];
	}
	
	private function attemptTableCount()
	{
		$query = 'select count(*) as count from attempts';
		$rows = $this->db->select($query);
		return $rows[0]['count'];
	}
	
	private function getNewUser()
	{
		$query = 'select * from users order by id desc limit 1';
		$rows = $this->db->select($query);
		if (count($rows) > 0)
		{
			return $rows[0];
		}
	}
	
	private function getRequests($userID)
	{
		$query = 'select * from requests where userID = ' . $userID;
		$rows = $this->db->select($query);
		return $rows;
	}
}

class CookiesMock implements CookieDelegate_i
{
	private $items = [];
	
	public function setCookie($name, $value = "",  $expire = 0,  $path = "", $domain = "", $secure = false, $httponly = false)
	{
		$this->items[$name] = $value;
	}
	
	public function getCookie($name)
	{
		return $this->items[$name];
	}
}

class ConfigMock
{
	public $attack_mitigation_time = '+30 minutes';
	public $attempts_before_ban = 30;
	public $bcrypt_cost = 10;
	public $cookie_forget = '+60 minutes';
	public $cookie_remember = '+1 month';
	public $email = true;
	public $email_send_disguise = 0;
	public $language = 'en_GB';
	public $site_name = 'Test App';
	public $site_activation_page = 'activate';
	public $site_password_reset_page = 'reset-password';
	public $site_timezone = 'America/Denver';
	public $site_key = 'fghuior.)/!/jdUkd8s2!7HVHG7777ghg';
	public $site_url = 'http://localhost:80';
	public $sms = true;
	public $verify_email_max_length = 254;
	public $verify_email_min_length = 5;
	public $verify_email_use_banlist = true;
	public $verify_password_min_length = 3;
	public $request_key_expiration = '+60 minutes';
}

?>