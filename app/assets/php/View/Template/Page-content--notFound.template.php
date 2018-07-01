<?php

include(__ASSETS_PATH . '/php/View/Template/Page-title.template.php');


$pageSectionModel = new PageSectionModel();
$pageSectionModel->title = 'The page was not found';

$pageSectionView = new PageSectionView($pageSectionModel, $currentUser);
echo $pageSectionView->output();

?>