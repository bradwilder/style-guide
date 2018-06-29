class Modal_Sessions
{
	constructor()
	{
		this.modals = $('#sessionsModal');
		this.events();
	}
	
	events()
	{
		this.modals.on("modal-init", function(e, target)
		{
			var $modal = $(this);
			var $modalBody = $modal.find('.modal-body');
			$modalBody.empty();
			
			var selectedUserID = $(target).attr('data-id');
			
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
