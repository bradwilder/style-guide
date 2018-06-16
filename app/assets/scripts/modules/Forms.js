class Forms
{
	constructor()
	{
		this.events();
	}
	
	events()
	{
		$('body').submit(function(e)
		{
			if ($(e.target).is('form'))
			{
				var $form = $(e.target);
				
				var $formData = new FormData($form[0]);
				
				$form.find('[data-original-value]').each(function()
				{
					var originalValue = $(this).attr('data-original-value');
					var newValue = $(this).val();
					if (originalValue == newValue)
					{
						var key = $(this).attr('name');
						$formData.delete(key);
					}
				});
				
				var action = $form.attr('data-action');
				if (action)
				{
					$formData.set('action', action);
				}
				
				var url = $form.attr('data-url');
				
				var redirect = $form.attr('data-redirect');
				
				var refresh = $form.attr('data-refresh');
				
				e.preventDefault();
				
				$.ajax
				({
					url: url,
					type: 'POST',
					data: $formData,
					contentType: false,
					processData: false,
					success: function(data)
					{
						console.log(data);
						if (data.message && data.showMessage)
						{
							alert(data.message);
						}
						
						if (redirect)
						{
							window.location=redirect;
						}
						else if (refresh != 'false')
						{
							location.reload();
						}
						else
						{
							$form.trigger('formSubmit');
						}
					}
				});
			}
		});
	}
}

export default Forms;