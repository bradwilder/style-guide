class Modal_Requests
{
	constructor()
	{
		this.modals = $('#requestsModal');
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
				url: '/requestList',
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

export default Modal_Requests;
