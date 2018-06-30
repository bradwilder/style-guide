class CommentReply
{
	constructor()
	{
		this.overlayClear = $('.editable-section__overlay--clear');
		
		this.events();
	}
	
	events()
	{
		var _this = this;
		
		$('body').click(function(e)
		{
			if ($(e.target).hasClass('comment__reply-button'))
			{
				var $section = $(e.target).closest('.editable-section');
				
				$section.closest_descendent('.comment__body').append($('#commentReplyForm').clone());
				
				$section.addClass('editable-section--editing');
				$section.find('.editable-section__input').first().focus();
				_this.overlayClear.show();
			}
			
			if ($(e.target).hasClass('comment__reply-submit') || $(e.target).hasClass('editable-section__overlay--clear'))
			{
				var $section = $('.editable-section--editing');
				
				var reply = $section.find('.comment__body.editable-section__input').val();
				$section.find('.editable-section__form').remove();
				
				$section.removeClass('editable-section--editing');
				_this.overlayClear.hide();
				
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
