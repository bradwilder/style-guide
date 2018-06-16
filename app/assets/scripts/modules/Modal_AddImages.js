class Modal_AddImages
{
	constructor()
	{
		this.modals = $('#addImagesModal');
		this.events();
	}
	
	events()
	{
		this.modals.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			$modal.find('.edit-image').remove();
			
			var sectionID = $(trigger).closest('.popover').attr('data-ID');
			
			var $modalBody = $modal.find('.modal-body');
			
			$.ajax
			({
				url: '/moodboardSection',
				type: 'GET',
				data: 'section_id=' + sectionID + '&action=additionalImages',
				success: function(data)
				{
					$modalBody.append(data);
				}
			});
		});
	}
}

export default Modal_AddImages;
