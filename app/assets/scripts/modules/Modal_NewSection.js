class Modal_NewSection
{
	constructor()
	{
		this.modals = $('#newSectionModal');
		this.events();
	}
	
	events()
	{
		this.modals.on("modal-init", function(e)
		{
			var $modal = $(this);
			
			var $select = $modal.find('[name=mode]');
			$select.empty();
			
			$.ajax
			({
				url: '/moodboardSection',
				type: 'GET',
				data: 'action=modes',
				dataType: 'json',
				success: function(data)
				{
					for (var i = 0; i < data.length; i++)
					{
						var row = data[i];
						$select.append('<option value="' + row["id"] + '">' + row["name"] + '</option>');
					}
					
					$select.find('option:first').prop('selected', true);
				}
			});
		});
	}
}

export default Modal_NewSection;