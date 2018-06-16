class AjaxError
{
	constructor()
	{
		this.events();
	}
	
	events()
	{
		$(document).ajaxError(function(event, request, settings, exception)
		{
			if (request.status != 401)
			{
				if (request.responseJSON)
				{
					var response = request.responseJSON;
					if (response.message)
					{
						console.log(response.message);
						alert(response.message);
					}
					else if (response.error)
					{
						console.log('Error: ' + response.error);
						alert('Error: ' + response.error);
					}
				}
			}
			else
			{
				var errorObj = request.responseJSON;
				var message = errorObj.message;
				var error = errorObj.error;
				
				if (errorObj.inactive && errorObj.expired)
				{
					var resendLink = 'Click <a href="/resend-activation">here</a> to send another activation email.';
					
					if (error)
					{
						error += (' ' + resendLink);
					}
					else
					{
						error = resendLink;
					}
				}
				
				var URLparam = '';
				if (message && message != '')
				{
					URLparam = '?message=' + encodeURIComponent(message);
				}
				else if (error && error != '')
				{
					URLparam = '?error=' + encodeURIComponent(error);
				}
				
				if (errorObj.inactive)
				{
					window.location="/activate" + URLparam;
				}
				else
				{
					window.location="/login" + URLparam;
				}
			}
		});
	}
}

export default AjaxError;