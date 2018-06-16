<?php

class UserController extends Controller_base
{
	public function __construct(UserModel $model)
	{
        parent::__construct($model);
    }
	
	public function emailExists()
	{
		echo User::nameExists($_POST['newValue'], $_POST['self_id']);
	}
	
	public function add()
	{
		$this->model->email = $_POST['email'];
		$this->model->phone = $_POST['phone'];
		$this->model->displayName = $_POST['displayName'];
		$this->model->groupID = $_POST['group'];
		
		$ret = $this->model->add();
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', array('error' => 'Error: ' . $ret));
		}
	}
	
	public function edit()
	{
		$this->model->userID = $_POST['id'];
		$this->model->email = $_POST['email'];
		$this->model->phone = $_POST['phone'];
		$this->model->displayName = $_POST['displayName'];
		$this->model->groupID = $_POST['group'];
		
		$ret = $this->model->edit();
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', array('error' => 'Error: ' . $ret));
		}
	}
	
	public function groups()
	{
		echo json_encode($this->model->getGroups());
	}
	
	public function delete()
	{
		$this->model->userID = $_POST['user_id_delete'];
		$this->model->currUserID = $_POST['user_id_current'];
		$this->model->currUserPassword = $_POST['password'];
		
		$this->model->delete();
	}
	
	public function undelete()
	{
		$this->model->userID = $_POST['id'];
		
		$ret = $this->model->undelete();
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', array('error' => 'Error: ' . $ret));
		}
	}
	
	public function changePassword()
	{
		$this->model->userID = $_POST['id'];
		$this->model->oldPassword = $_POST['old'];
		$this->model->newPassword = $_POST['new'];
		$this->model->newPasswordConfrim = $_POST['new-confirm'];
		
		$ret = $this->model->changePassword();
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', array('error' => 'Error: ' . $ret));
		}
	}
	
	public function activationRequest()
	{
		$this->model->userID = $_POST['id'];
		
		$ret = $this->model->activationRequest();
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', array('error' => 'Error: ' . $ret));
		}
	}
	
	public function activate()
	{
		$this->model->emailKey = $_POST['email-key'];
		$this->model->smsKey = $_POST['sms-key'];
		$this->model->newPassword = $_POST['new-password'];
		$this->model->newPasswordConfirm = $_POST['new-password-confirm'];
		
		$ret = $this->model->activate();
		if ($ret['error'])
		{
			setReturnHeaders(401, 'Unauthorized', $ret);
		}
		else
		{
			setReturnHeaders(200, 'OK', $ret);
		}
	}
	
	public function resendActivation()
	{
		$this->model->emailKey = $_POST['email-key'];
		$this->model->smsKey = $_POST['sms-key'];
		
		$ret = $this->model->resendActivation();
		if ($ret['error'])
		{
			setReturnHeaders(500, 'Internal Server Error', $ret);
		}
		else
		{
			setReturnHeaders(200, 'OK', $ret);
		}
	}
	
	public function deleteRequest()
	{
		$this->model->requestIDs = $_POST['id'];
		
		$ret = $this->model->deleteRequests();
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', array('error' => 'Error: ' . $ret));
		}
	}
	
	public function logout()
	{
		echo $this->model->logout();
	}
	
	public function login()
	{
		$this->model->email = $_POST['email'];
		$this->model->currUserPassword = $_POST['password'];
		
		$ret = $this->model->login();
		if ($ret['error'])
		{
			setReturnHeaders(401, 'Unauthorized', $ret);
		}
		else
		{
			setReturnHeaders(200, 'OK', $ret);
		}
	}
	
	public function requestReset()
	{
		$this->model->email = $_POST['email'];
		
		$ret = $this->model->requestReset();
		if ($ret['error'])
		{
			setReturnHeaders(500, 'Internal Server Error', $ret);
		}
		else
		{
			setReturnHeaders(200, 'OK', $ret);
		}
	}
	
	public function resetPassword()
	{
		$this->model->emailKey = $_POST['email-key'];
		$this->model->smsKey = $_POST['sms-key'];
		$this->model->newPassword = $_POST['new-password'];
		$this->model->newPasswordConfirm = $_POST['new-password-confirm'];
		
		$ret = $this->model->resetPassword();
		if ($ret['error'])
		{
			setReturnHeaders(500, 'Internal Server Error', $ret);
		}
		else
		{
			setReturnHeaders(200, 'OK', $ret);
		}
	}
}

?>