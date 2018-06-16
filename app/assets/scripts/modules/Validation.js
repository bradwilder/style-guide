class Validation
{
	constructor()
	{
		this.validators = $('.iv_inputValidator');
		this.init();
		this.events();
	}
	
	init()
	{
		this.validators.each(function()
		{
			if (this.hasAttribute('data-initially-valid'))
			{
				$(this).valid();
			}
		});
	}
	
	events()
	{
		this.validators.on('input', function(e)
		{
			var $input = $(this);
			var newValue = $input.val();
			
			var selfStr = '';
			if (this.hasAttribute('data-except-self'))
			{
				var selfID = $input.attr('data-except-self');
				selfStr = '&self_id=' + selfID;
			}
			
			var parentStr = '';
			if (this.hasAttribute('data-parent'))
			{
				var parentID = $input.attr('data-parent');
				parentStr = '&parent_id=' + parentID;
			}
			
			var parentSubStr = '';
			if (this.hasAttribute('data-parent-sub'))
			{
				var parentSubID = $input.attr('data-parent-sub');
				parentSubStr = '&parent_sub_id=' + parentSubID;
			}
			
			var url;
			var $form = $input.closest('form');
			if ($form)
			{
				url = $form.attr('data-url');
			}
			
			if (newValue != "")
			{
				$.ajax
				({
					url: url,
					type: 'POST',
					data: 'newValue=' + encodeURIComponent(newValue) + selfStr + parentStr + parentSubStr + '&action=' + $input.attr("name") + 'Exists',
					success: function(data)
					{
						if (data)
						{
							$input.invalid();
						}
						else
						{
							$input.valid();
						}
					}
				});
			}
			else
			{
				$input.invalid();
			}
		});
	}
}

export default Validation;