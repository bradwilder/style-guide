class DeleteSection
{
	constructor()
	{
		this.events();
	}
	
	events()
	{
		$('body').click(function(e)
		{
			if ($(e.target).hasClass('delete-section'))
			{
				if (confirm("Are you sure you want to delete the section?" ))
				{
					var sectionID = $(e.target).closest('.popover').attr('data-id');
					
					$.ajax
					({
						url: '/moodboardSection',
						type: 'POST',
						data: 'section_id=' + sectionID + '&action=delete',
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

export default DeleteSection;
