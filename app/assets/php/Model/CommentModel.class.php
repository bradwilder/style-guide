<?php

class CommentModel extends Model_base
{
	public function submit(int $userID, string $commentText, int $commentReplyingToID, int $sectionImageID)
	{
		$comment = new Comment($this->db);
		$comment->userID = $userID;
		$comment->text = $commentText;
		$comment->postTime = time();
		$comment->commentReplyingToID = $commentReplyingToID;
		$comment->sectionImageID = $sectionImageID;
		$comment->write();
	}
}

?>