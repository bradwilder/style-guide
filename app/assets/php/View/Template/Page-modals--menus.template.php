<?php

if (!function_exists('echoModal'))
{
	function echoModal($id, $title, $template)
	{
		$modalModel = new ModalModel();
		
		$modalModel->id = $id;
		$modalModel->title = $title;
		$modalModel->template = $template;

		$modalView = new ModalView($modalModel);
		echo $modalView->output();
	}
}

echoModal('changePasswordModal', 'Change Password', 'Modal--changePassword.template.php');

?>