class DeleteRequest
{
	constructor()
	{
		this.events();
	}
	
	events()
	{
		$('body').click(function(e)
		{
			if ($(e.target).hasClass('requests-table__delete'))
			{
				if (confirm("Are you sure you want to delete the request?" ))
				{
					var requestID = $(e.target).closest('tr').attr('data-id');
					
					$.ajax
					({
						url: '/user',
						type: 'POST',
						data: 'id=' + requestID + '&action=deleteRequest',
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

export default DeleteRequest;
