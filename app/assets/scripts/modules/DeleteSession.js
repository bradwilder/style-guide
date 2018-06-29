class DeleteSession
{
	constructor()
	{
		$('body').click(function(e)
		{
			if ($(e.target).hasClass('sessions-table__delete'))
			{
				if (confirm("Are you sure you want to delete the session?" ))
				{
					var sessionID = $(e.target).closest('tr').attr('data-id');
					
					$.ajax
					({
						url: '/session',
						type: 'POST',
						data: 'id=' + sessionID + '&action=delete',
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

export default DeleteSession;
