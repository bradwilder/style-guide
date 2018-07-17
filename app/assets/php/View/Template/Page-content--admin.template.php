<?php

include(__ASSETS_PATH . '/php/View/Template/Page-title.template.php');


$options = new PageSectionEditableOptions('Root');

$button = new PageSectionEditableButton('fa fa-plus', 'data-modal="#newUserModal"');
$buttons = [$button];

$pageSectionModel = new PageSectionModel(null, $options, $buttons);
$pageSectionModel->title = 'Users';
$pageSectionModel->content = MVCoutput(UserListModel, Controller_base, SimpleView, 'UserListView--table.template.php', $currentUser, null);

$pageSectionView = new PageSectionView($pageSectionModel, $currentUser);
echo $pageSectionView->output();


$pageSectionModel = new PageSectionModel();
$pageSectionModel->title = 'Branding';

$pageSectionView = new PageSectionView($pageSectionModel, $currentUser);
echo $pageSectionView->output();


$pageSectionModel = new PageSectionModel();
$pageSectionModel->title = 'Page Options';
$pageSectionModel->content =
'
	<div class="page-options-container">
		<page-options-table :table-data="tableData"></page-options-table>
	</div>
';

$pageSectionView = new PageSectionView($pageSectionModel, $currentUser);
echo $pageSectionView->output();


include(__ASSETS_PATH . '/php/View/Template/PageOptionsComponents.template.php');

?>