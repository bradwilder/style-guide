<?php

class UserController extends Controller_base
{
	public function __construct(UserModel $model)
	{
        parent::__construct($model);
    }
	
	public function emailExists()
	{
		echo $this->model->nameExists($_POST['newValue'], $_POST['self_id']);
	}
	
	public function add()
	{
		$ret = $this->model->add($_POST['email'], $_POST['group'], $_POST['phone'], $_POST['displayName']);
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function edit()
	{
		$ret = $this->model->edit($_POST['id'], $_POST['email'], $_POST['phone'], $_POST['displayName'], $_POST['group']);
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function groups()
	{
		echo json_encode($this->model->getGroups());
	}
	
	public function delete()
	{
		$this->model->delete($_POST['user_id_delete'], $_POST['user_id_current'], $_POST['password']);
	}
	
	public function undelete()
	{
		$ret = $this->model->undelete($_POST['id']);
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function changePassword()
	{
		$ret = $this->model->changePassword($_POST['id'], $_POST['old'], $_POST['new'], $_POST['new-confirm']);
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function activationRequest()
	{
		$ret = $this->model->activationRequest($_POST['id']);
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function activate()
	{
		$ret = $this->model->activate($_POST['email-key'], $_POST['new-password'], $_POST['new-password-confirm'], $_POST['sms-key']);
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
		$ret = $this->model->resendActivation($_POST['email-key'], $_POST['sms-key']);
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
		$ret = $this->model->deleteRequests($_POST['id']);
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function logout()
	{
		echo $this->model->logout();
	}
	
	public function login()
	{
		$ret = $this->model->login($_POST['email'], $_POST['password']);
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
		$ret = $this->model->requestReset($_POST['email']);
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
		$ret = $this->model->resetPassword($_POST['email-key'], $_POST['new-password'], $_POST['new-password-confirm'], $_POST['sms-key']);
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