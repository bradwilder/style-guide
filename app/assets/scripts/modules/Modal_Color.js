class Modal_Color
{
	constructor()
	{
		this.editColorModal = $('#editColorModal');
		this.events();
	}
	
	events()
	{
		this.editColorModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			var selectedID = $(trigger).closest('tr').attr('data-id');
			
			$modal.find('[name=color_id]').val(selectedID);
			
			$.ajax
			({
				url: '/colors',
				type: 'GET',
				data: 'color_id=' + selectedID + '&action=get',
				dataType: 'json',
				success: function(data)
				{
					var $nameInput = $modal.find('[name=name]');
					$nameInput.val(data.name);
					$nameInput.attr('data-except-self', selectedID);
					$nameInput.attr('data-original-value', data.name);
					
					var $hexInput = $modal.find('[name=hex]');
					$hexInput.val(data.hex);
					$hexInput.attr('data-original-value', data.hex);
					
					var $var1Input = $modal.find('[name=var1]');
					$var1Input.val(data.variant1);
					$var1Input.attr('data-original-value', data.variant1);
					
					var $var2Input = $modal.find('[name=var2]');
					$var2Input.val(data.variant2);
					$var2Input.attr('data-original-value', data.variant2);
				}
			});
		});
	}
	
	_onHexInput()
	{
		
	}
}

export default Modal_Color;