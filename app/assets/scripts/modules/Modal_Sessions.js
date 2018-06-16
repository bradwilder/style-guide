class Modal_Sessions
{
	constructor()
	{
		this.modals = $('#sessionsModal');
		this.events();
	}
	
	events()
	{
		this.modals.on("modal-init", function(e)
		{
			var $modal = $(this);
			var $modalBody = $modal.find('.modal-body');
			$modalBody.empty();
			
			var $selectedRow = $('#user-table').getLeadHighlighted();
			var selectedUserID = $selectedRow.find('.users-table__id').html();
			
			$.ajax
			({
				url: '/sessionList',
				type: 'GET',
				data: 'user_id=' + selectedUserID,
				success: function(data)
				{
					$modalBody.append(data);
				}
			});
		});
	}
}

export default Modal_Sessions;
