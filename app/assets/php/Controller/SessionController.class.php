<?php

class SessionController extends Controller_base
{
	public function __construct(SessionModel $model)
	{
		parent::__construct($model);
	}
	
	public function delete()
	{
		$deleted = $this->model->deleteSession($_POST['id']);
		if (!$deleted)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: Unable to delete the session.']);
		}
	}
	
	public function refresh()
	{
		$this->model->refreshSession();
	}
}

?>