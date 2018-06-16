<?php include(__ASSETS_PATH . '/php/View/Template/Page-header.template.php'); ?>

<body class="login">
	<?php include(__ASSETS_PATH . '/php/View/Template/Credential-page-alert.template.php'); ?>
	
	<div class="login__form">
		<h1 class="login__form__title type__title"><?=$pageTitle?></h1>
		<form data-url="/user" data-redirect-url="<?=$_REQUEST['redir']?>" class="type__desc" method='post' role="form">
			<?php include(__ASSETS_PATH . '/php/View/Template/Credential-page-email.template.php'); ?>
			<?php include(__ASSETS_PATH . '/php/View/Template/Credential-page-password.template.php'); ?>
			
			<button class="login__form__button login__reset-toggle btn btn-danger" type="button"><span class="login__reset-toggle__text"></span> <i class="login__reset-toggle__arrow fa"></i></button>
			
			<button class="login__form__button btn btn-primary" name="submit" type="submit"></button>
		</form>
	</div>

<?php include(__ASSETS_PATH . "/php/View/Template/Page-footer--$pageCode.template.php"); ?>