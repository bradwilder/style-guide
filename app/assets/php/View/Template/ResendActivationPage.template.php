<?php include(__ASSETS_PATH . '/php/View/Template/Page-header.template.php'); ?>

<body class="login">
	<?php include(__ASSETS_PATH . '/php/View/Template/Credential-page-alert.template.php'); ?>
	
	<div class="login__form">
		<h1 class="login__form__title type__title"><?=$pageTitle?></h1>
		<form data-url="/user" data-action="resendActivation" data-refresh="false" class="type__desc" method='post' role="form">
			<?php include(__ASSETS_PATH . '/php/View/Template/Credential-page-email-key.template.php'); ?>
			<?php include(__ASSETS_PATH . '/php/View/Template/Credential-page-sms-key.template.php'); ?>
			
			<button class="login__form__button btn btn-primary" name="submit" type="submit">Send</button>
		</form>
	</div>

<?php include(__ASSETS_PATH . "/php/View/Template/Page-footer--$pageCode.template.php"); ?>