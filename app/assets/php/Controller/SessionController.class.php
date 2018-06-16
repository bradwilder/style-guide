<?php

class SessionController extends Controller_base
{
	public function __construct(SessionModel $model)
	{
        parent::__construct($model);
    }
	
	public function delete()
	{
		$this->model->setSessionID($_POST['id']);
		
		$deleted = $this->model->deleteSession();
		if (!$deleted)
		{
			setReturnHeaders(500, 'Internal Server Error', array('error' => 'Error: Unable to delete the session.'));
		}
	}
	
	public function refresh()
	{
		$this->model->refreshSession();
	}
}

?>