class RemoveImage
{
	constructor()
	{
		this.events();
	}
	
	events()
	{
		$('body').click(function(e)
		{
			if ($(e.target).hasClass('remove-image'))
			{
				if (confirm("Are you sure you want to remove the image from this section?" ))
				{
					var sectionImageID = $(e.target).closest('.popover').attr('data-id');
					
					$.ajax
					({
						url: '/moodboardSection',
						type: 'POST',
						data: 'section_image_id=' + sectionImageID + '&action=removeImage',
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

export default RemoveImage;
