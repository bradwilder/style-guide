<?php

class CommentController extends Controller_base
{
	public function __construct(CommentModel $model)
	{
		parent::__construct($model);
	}
	
	public function submit()
	{
		$this->model->submit($_POST['user_id'], $_POST['comment']);
	}
	
	public function reply()
	{
		$this->model->submit($_POST['user_id'], $_POST['comment'], $_POST['replying_id']);
	}
}

?>