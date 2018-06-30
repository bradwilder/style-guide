<?php

include(__ASSETS_PATH . '/php/View/Template/Page-title.template.php');


echo MVCoutput(MoodboardSectionsModel, Controller_base, SimpleView, 'MoodboardSections.template.php', $currentUser, null);


$commentsModel = new CommentsModel();

$commentsController = new Controller_base($commentsModel);
$commentsController->index();

$commentsView = new CommentsView($commentsModel, $currentUser);
$commentsContent = $commentsView->output();


$pageSectionModel = new PageSectionModel($commentsModel);

$pageSectionModel->title = 'Comments';
$commentCount = $pageSectionModel->sectionModel->getCommentCount();
$pageSectionModel->description = $commentCount . ' comment' . ($commentCount == 1 ? '' : 's');
$pageSectionModel->content = $commentsContent;

$pageSectionView = new PageSectionView($pageSectionModel, $currentUser);
echo $pageSectionView->output();

?>