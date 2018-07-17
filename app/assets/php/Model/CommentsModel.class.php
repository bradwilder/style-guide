<?php

class CommentsModel extends Model_base
{
	public $sectionImageID;
	private $comments;
	
	public function getComments()
	{
		$comments = [];
		$rows = $this->db->select('select id from mb_comment where sectionImageID <=> ? order by commentReplyingToID, postTime', 'i', [&$this->sectionImageID]);
		foreach ($rows as $row)
		{
			$comment = new Comment($this->db, $row['id']);
			$comment->read();
			$comment->readExtra();
			
			$comments []= $comment;
		}
		
		return $this->comments = $comments;
	}
	
	public function getCommentCount()
	{
		if (!$this->comments)
		{
			$this->getComments();
		}
		
		return count($this->comments);
	}
}

?>