<?php

include(__ASSETS_PATH . '/php/View/Template/Page-title.template.php');


echo MVCoutput(MoodboardSectionsModel, MoodboardSectionsController, MoodboardSectionsView, null, $currentUser, null);


$commentsModel = new CommentsModel();

$commentsController = new CommentsController($commentsModel);
$commentsController->index();

$commentsView = new CommentsView($commentsModel);
$commentsView->currentUser = $currentUser;
$commentsContent = $commentsView->output();


$pageSectionModel = new PageSectionModel($commentsModel);

$pageSectionModel->title = 'Comments';
$commentCount = $pageSectionModel->sectionModel->getCommentCount();
$pageSectionModel->description = $commentCount . ' comment' . ($commentCount == 1 ? '' : 's');
$pageSectionModel->content = $commentsContent;

$pageSectionView = new PageSectionView($pageSectionModel);
$pageSectionView->currentUser = $currentUser;
echo $pageSectionView->output();

?>