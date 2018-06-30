<?php

if (!function_exists('echoModal'))
{
	function echoModal($id, $title, $template)
	{
		$modalModel = new ModalModel();
		
		$modalModel->id = $id;
		$modalModel->title = $title;
		$modalModel->template = $template;

		$modalView = new ModalView($modalModel, $currentUser);
		echo $modalView->output();
	}
}


echoModal('newUserModal', 'New User', 'Modal--addUser.template.php');
echoModal('editUserModal', 'Edit User', 'Modal--editUser.template.php');
echoModal('deleteUserModal', 'Delete User', 'Modal--deleteUser.template.php');
echoModal('sessionsModal', 'Sessions', 'Modal--empty.template.php');
echoModal('requestsModal', 'Requests', 'Modal--empty.template.php');

?>