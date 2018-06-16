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
				var $row = $(e.target).closest('tr');
				var userID = $row.find('.users-table__id').html();
				
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
