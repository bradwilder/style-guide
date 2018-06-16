class CommentReply
{
	constructor()
	{
		this.events();
	}
	
	events()
	{
		$('body').click(function(e)
		{
			if ($(e.target).hasClass('comment__reply-button'))
			{
				var $section = $(e.target).closest('.editable-section');
				
				$section.find('.editable-section__button-edit').removeClass('editable-section__button--show');
				
				$section.closest_descendent('.comment__body').append($('.commentReplyForm').clone());
				$section.closest_descendent('.commentReplyForm').show();
				
				$section.addClass('editable-section--editing');
				$section.find('.editable-section__input').first().focus();
				$('.editable-section__overlay--clear').show();
			}
			
			if ($(e.target).hasClass('comment__reply-submit') || $(e.target).hasClass('editable-section__overlay--clear'))
			{
				var $section = $('.editable-section--editing');
				
				$section.find('.editable-section__button-edit').addClass('editable-section__button--show');
				
				var reply = $section.find('.comment__body.editable-section__input').val();
				$section.find('.commentReplyForm').remove();
				
				$section.removeClass('editable-section--editing');
				$('.editable-section__overlay--clear').hide();
				
				if ($(e.target).hasClass('comment__reply-submit'))
				{
					var replyingID = $section.find('input[name="comment_id"]').val();
					
					$.ajax
					({
						url: '/comment',
						type: 'POST',
						data: 'user_id=' + $('[data-user-id]').attr('data-user-id') + '&replying_id=' + replyingID + '&comment=' + encodeURIComponent(reply) + '&action=reply',
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

export default CommentReply;
