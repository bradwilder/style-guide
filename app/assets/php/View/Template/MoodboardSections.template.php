<?php foreach($data as $section) {
	$submittableOptions = new PageSectionSubmittableOptions($section->id, '/moodboardSection');
	
	$options = new PageSectionEditableOptions('Edit', $submittableOptions);
	
	$button = new PageSectionEditableButton('fa fa-pencil', 'data-popover-trigger="#editSectionPopover" data-popover-source-id="' . $section->id . '"');
	$buttons = array($button);
	
	$pageSectionModel = new PageSectionModel(null, $options, $buttons);
	$pageSectionModel->title = htmlentities($section->name);
	$pageSectionModel->description = htmlentities($section->description);
	$pageSectionModel->content = MVCoutput(MoodboardSectionContentModel, MoodboardSectionContentController, SimpleView, 'MoodboardSectionContent.template.php', $currentUser, null, array($section));
	
	$pageSectionView = new PageSectionView($pageSectionModel, $currentUser);
	echo $pageSectionView->output();
} ?>