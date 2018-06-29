class ActivationRequest
{
	constructor()
	{
		this.events();
	}
	
	events()
	{
		$('body').click(function(e)
		{
			if ($(e.target).hasClass('activate-request'))
			{
				var userID = $(e.target).attr('data-id');
				
				$.ajax
				({
					url: '/user',
					type: 'POST',
					data: 'id=' + userID + '&action=activationRequest',
					success: function(data)
					{
						alert('Activation email has been sent.');
						location.reload();
					}
				});
			}
		});
	}
}

export default ActivationRequest;
