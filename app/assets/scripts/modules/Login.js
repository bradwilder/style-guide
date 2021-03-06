class Login
{
	constructor()
	{
		this.resetToggle = $('.login__reset-toggle');
		
		this.init();
		this.events();
	}
	
	init()
	{
		if (this.resetToggle.length > 0)
		{
			showLogin(this.resetToggle);
		}
	}
	
	events()
	{
		this.resetToggle.click(toggleLoginReset);
	}
}

function toggleLoginReset(e)
{
	var action = $(e.target).closest('form').attr('data-action');
	if (action == 'login')
	{
		showReset($(e.target));
	}
	else
	{
		showLogin($(e.target));
	}
}

function showLogin($target)
{
	var $form = $target.closest('form');
	$form.find('.login__password-group').prop('disabled', false).fadeIn(200);
	$form.find('[type=password]').attr('required', 'required');
	$form.find('[type=submit]').html('Login');
	$('.login__form .login__form__title').text('Login');
	$form.attr('data-action', 'login');
	$form.attr('data-refresh', '');
	$form.attr('data-redirect', $form.attr('data-redirect-url') ? $form.attr('data-redirect-url') : '/');
	$form.find('.login__reset-toggle__text').text('I forgot my password');
	$form.find('.login__reset-toggle__arrow').removeClass('fa-arrow-circle-o-left').addClass('fa-arrow-circle-o-right');
}

function showReset($target)
{
	var $form = $target.closest('form');
	$form.find('.login__password-group').prop('disabled', true).fadeOut(200);
	$form.find('[type=password]').removeAttr('required');
	$form.find('[type=submit]').html('Reset Password');
	$('.login__form .login__form__title').text('Reset Password');
	$form.attr('data-action', 'requestReset');
	$form.attr('data-refresh', 'false');
	$form.attr('data-redirect', '');
	$form.find('.login__reset-toggle__text').text('I remember my password');
	$form.find('.login__reset-toggle__arrow').removeClass('fa-arrow-circle-o-right').addClass('fa-arrow-circle-o-left');
}

export default Login;
