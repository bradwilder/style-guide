<?php

class MoodboardPageController extends PageController
{
	public function index()
	{
		$this->setPageData('Mood Board', 'moodboard', true);
	}
}

?>