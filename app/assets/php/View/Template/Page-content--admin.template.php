<?php include(__ASSETS_PATH . '/php/View/Template/Page-title.template.php'); ?>

<section class="page-section <?php if (in_array('Root', $currentUser->roles)) {echo 'editable-section';} ?>">
	<div class="page-section__header">
		<?php if (in_array('Root', $currentUser->roles)) { ?>
			<div class="page-section__edit-button editable-section__button-container">
				<a tabindex="-1" role="button" class="editable-section__button fa fa-plus" data-modal="#newUserModal"></a>
			</div>
		<?php } ?>
		
		<h1 class="page-section__title type__title">Users</h1>
	</div>
	
	<?php echo MVCoutput(UserListModel, Controller_base, SimpleView, 'UserListView--table.template.php', $currentUser, null); ?>
</section>

<section class="page-section">
	<div class="page-section__header">
		<h1 class="page-section__title type__title">Branding</h1>
	</div>
	
	
</section>

<section class="page-section">
	<div class="page-section__header">
		<h1 class="page-section__title type__title">Page Options</h1>
	</div>
	
	<div class="page-options-container">
		<page-options-table :table-data="tableData"></page-options-table>
	</div>
</section>

<?php include(__ASSETS_PATH . '/php/View/Template/PageOptionsComponents.template.php'); ?>