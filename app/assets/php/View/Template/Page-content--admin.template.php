<?php include(__ASSETS_PATH . '/php/View/Template/Page-title.template.php'); ?>

<section class="page-section <?php if (in_array('Root', $currentUser->roles)) {echo 'editable-section';} ?>">
	<?php
		$button = new PageSectionEditableButton('fa fa-plus', 'data-modal="#newUserModal"');
		
		$options = new PageSectionEditableOptions('Root');
		$options->addButton($button);
		
		$model = new PageSectionHeaderModel('Users', $options);
		$view = new PageSectionHeaderView($model, $currentUser);
		echo $view->output();
	?>
	
	<?php echo MVCoutput(UserListModel, Controller_base, SimpleView, 'UserListView--table.template.php', $currentUser, null); ?>
</section>

<section class="page-section">
	<?php
		$model = new PageSectionHeaderModel('Branding');
		$view = new PageSectionHeaderView($model, $currentUser);
		echo $view->output();
	?>
	
	
</section>

<section class="page-section">
	<?php
		$model = new PageSectionHeaderModel('Page Options');
		$view = new PageSectionHeaderView($model, $currentUser);
		echo $view->output();
	?>
	
	<div class="page-options-container">
		<page-options-table :table-data="tableData"></page-options-table>
	</div>
</section>

<?php include(__ASSETS_PATH . '/php/View/Template/PageOptionsComponents.template.php'); ?>