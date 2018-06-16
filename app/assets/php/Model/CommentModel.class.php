<?php

class CommentModel extends Model_base
{
	public $userID;
	public $commentText;
	public $commentReplyingTo;
	public $sectionImageID;
	
	public function submit()
	{
		if ($this->userID && $this->commentText)
		{
			$comment = new Comment($this->db);
			$comment->userID = $this->userID;
			$comment->text = $this->commentText;
			$comment->postTime = time();
			$comment->commentReplyingToID = $this->commentReplyingTo;
			$comment->sectionImageID = $this->sectionImageID;
			$comment->write();
		}
		else
		{
			throw new Exception('User ID and comment must be set');
		}
	}
}

?>