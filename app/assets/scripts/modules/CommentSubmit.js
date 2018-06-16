class CommentSubmit
{
	constructor()
	{
		this.buttons = $('.comment-submit__button');
		this.events();
	}
	
	events()
	{
		this.buttons.click(function()
		{
			var $form = $(this).closest('form');
			var formData = new FormData($form[0]);
			formData.set('user_id', $('[data-user-id]').attr('data-user-id'));
			
			$.ajax
			({
				url: '/comment',
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data)
				{
					location.reload();
				}
			});
		});
	}
}

export default CommentSubmit;
