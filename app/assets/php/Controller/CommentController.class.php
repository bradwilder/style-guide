<?php

class CommentController extends Controller_base
{
	public function __construct(CommentModel $model)
	{
        parent::__construct($model);
    }
	
	public function submit()
	{
		$this->model->userID = $_POST['user_id'];
		$this->model->commentText = $_POST['comment'];
		
		$this->model->submit();
	}
	
	public function reply()
	{
		$this->model->userID = $_POST['user_id'];
		$this->model->commentText = $_POST['comment'];
		$this->model->commentReplyingTo = $_POST['replying_id'];
		
		$this->model->submit();
	}
}

?>