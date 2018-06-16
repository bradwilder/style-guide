class Modal_ManageImages
{
	constructor()
	{
		this.modals = $('#manageImagesModal');
		this.events();
	}
	
	events()
	{
		this.modals.on("modal-init", function(e)
		{
			var $modal = $(this);
			var $modalBody = $modal.find('.modal-body');
			$modal.find('.edit-image').remove();
			
			$.ajax
			({
				url: '/moodboardImage',
				type: 'GET',
				success: function(data)
				{
					$modalBody.append(data);
					
					$modal.find('.edit-image__replace-input').on('change', replaceOnChange);
					$modal.find('.edit-image__delete').click(deleteOnClick);
				}
			});
		});
	}
}

function replaceOnChange()
{
	var proceed = confirm("Are you sure you want to replace the image?" );
	if (proceed)
	{
		var $form = $(this).closest('form');
		$form.submit();
	}
	else
	{
		$(this).val("");
	}
}

function deleteOnClick()
{
	var $button = $(this);
	var $editImage = $button.closest('.edit-image');
	var sectionsUsing = $editImage.find('ul.edit-image__list li').length;
	var proceed = true;
	if (sectionsUsing > 0)
	{
		proceed = confirm("This image is used by " + sectionsUsing + " section" + (sectionsUsing != 1 ? "s" : "") + ". Are you sure you want to delete it?" );
	}
	else
	{
		proceed = confirm("Are you sure you want to delete the image?" );
	}
	
	if (proceed)
	{
		var imageID = $button.siblings('.edit-image__replace-form').find('[name="image_id"]').val();
		$.ajax
		({
			url: '/moodboardImage',
			type: 'POST',
			data: 'image_id=' + imageID + '&action=delete',
			success: function(data)
			{
				location.reload();
			}
		});
	}
}

export default Modal_ManageImages;
