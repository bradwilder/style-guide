<?php

include(__ASSETS_PATH . '/php/View/Template/Page-header.template.php');
include(__ASSETS_PATH . '/php/View/Template/Page-body.template.php');
if ($useMenu)
{
	echo MVCoutput("PageMenusModel", "PageMenusController", "PageMenusView", null, $currentUser, null, [$pageCode]);
	include(__ASSETS_PATH . '/php/View/Template/Page-modals--menus.template.php');
}
include(__ASSETS_PATH . '/php/View/Template/Page-content-start.template.php');
include(__ASSETS_PATH . "/php/View/Template/Page-content--$pageCode.template.php");
include(__ASSETS_PATH . '/php/View/Template/Page-content-end.template.php');
include(__ASSETS_PATH . "/php/View/Template/Page-modals--$pageCode.template.php");

$model = new PageFooterModel($pageCode);
$view = new PageFooterView($model, $currentUser);
echo $view->output();

?>