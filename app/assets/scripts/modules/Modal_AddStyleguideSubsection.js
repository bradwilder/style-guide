class Modal_AddStyleguideSubsection
{
	constructor()
	{
		this.modals = $('#addSubsectionModal');
		this.events();
	}
	
	events()
	{
		this.modals.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			var sectionID = $(trigger).attr('data-section-id');
			$modal.find('[name=section_id]').val(sectionID);
			$modal.find('[name=name]').attr('data-parent', sectionID);
			
			var parentSubsectionID = $(trigger).attr('data-parent-sub-id');
			if (parentSubsectionID)
			{
				$modal.find('[name=parent_subsection_id]').val(parentSubsectionID);
				$modal.find('[name=name]').attr('data-parent-sub', parentSubsectionID);
			}
		});
	}
}

export default Modal_AddStyleguideSubsection;
