<?php

use Tree\Node\Node;

class CommentsView extends View_base
{
	public function __construct(CommentsModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/Comments--list.template.php'));
	}
	
	public function output()
	{
		$commentsList = $this->model->getComments();
		
		$root = new Node(null);
		$itemsHash = array();
		foreach ($commentsList as $comment)
		{
			$node = new Node($comment);
			
			if ($comment->commentReplyingToID)
			{
				$parent = $itemsHash[$comment->commentReplyingToID];
				$parent->addChild($node);
			}
			else
			{
				$root->addChild($node);
			}
			
			$itemsHash[$comment->id] = $node;
		}
		
		$hasPermission = in_array('Comment', $this->currentUser->roles);
		
		$this->template->commentsContentInner = $this->formatNode($root, $hasPermission);
		
		return parent::output();
	}
	
	private function formatNode($node, $hasPermission)
	{
		$childHTML = '';
		
		$children = $node->getChildren();
		foreach ($children as $child)
		{
			$childHTML .= $this->formatNode($child, $hasPermission);
		}
		
		$comment = $node->getValue();
		if ($comment)
		{
			ob_start();
			include(__ASSETS_PATH . '/php/View/Template/Comment.template.php');
			return ob_get_clean();
		}
		else
		{
			ob_start();
			include(__ASSETS_PATH . '/php/View/Template/Comment-list.template.php');
			return ob_get_clean();
		}
	}
}

?>