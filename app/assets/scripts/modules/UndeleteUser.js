class UndeleteUser
{
	constructor()
	{
		$('body').click(function(e)
		{
			if ($(e.target).hasClass('undelete-user'))
			{
				if (confirm("Are you sure you want to undelete the user?" ))
				{
					var userID = $(e.target).attr('data-id');
					
					$.ajax
					({
						url: '/user',
						type: 'POST',
						data: 'id=' + userID + '&action=undelete',
						success: function(data)
						{
							location.reload();
						}
					});
				}
			}
		});
	}
}

export default UndeleteUser;
