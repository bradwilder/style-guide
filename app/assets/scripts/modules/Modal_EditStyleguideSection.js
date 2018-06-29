class Modal_EditStyleguideSection
{
	constructor()
	{
		this.modals = $('#editSectionModal');
		this.events();
	}
	
	events()
	{
		this.modals.on("modal-init", function(e, trigger)
		{
			var $modal = $(e.target);
			var sectionID = $(trigger).attr('data-id');
			
			$modal.find('[name=section_id]').val(sectionID);
			$modal.find('[name=name]').attr('data-except-self', sectionID);
			
			$.ajax
			({
				url: '/section',
				type: 'GET',
				data: 'section_id=' + sectionID + '&action=get',
				dataType: 'json',
				success: function(data)
				{
					$modal.find('[name=name]').val(data.name);
				}
			});
		});
	}
}

export default Modal_EditStyleguideSection;
