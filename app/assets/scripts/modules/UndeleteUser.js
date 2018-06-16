class UndeleteUser
{
	constructor()
	{
		this.events();
	}
	
	events()
	{
		$('body').click(function(e)
		{
			if ($(e.target).hasClass('undelete-user'))
			{
				if (confirm("Are you sure you want to undelete the user?" ))
				{
					var $row = $(e.target).closest('tr');
					var userID = $row.find('.users-table__id').html();
					
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
