class Modal_EditStyleguideSubsection
{
	constructor()
	{
		this.modals = $('#editSubsectionModal');
		this.events();
	}
	
	events()
	{
		this.modals.on("modal-init", function(e, trigger)
		{
			var $modal = $(e.target);
			var subsectionID = $(trigger).attr('data-id');
			
			$modal.find('[name=subsection_id]').val(subsectionID);
			$modal.find('[name=name]').attr('data-except-self', subsectionID);
			
			$.ajax
			({
				url: '/subsection',
				type: 'GET',
				data: 'subsection_id=' + subsectionID + '&action=get',
				dataType: 'json',
				success: function(data)
				{
					$modal.find('[name=name]').val(data.name);
					$modal.find('[name=desc]').val(data.description);
				}
			});
		});
	}
}

export default Modal_EditStyleguideSubsection;
