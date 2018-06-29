class Modal
{
	constructor()
	{
		$('body').on('formSubmit', function(e)
		{
			if ($(e.target).is('.modal form'))
			{
				$(e.target).closest('.modal').modal('hide');
			}
		});
		
		$('body').click(function(e)
		{
			if (e.target.hasAttribute('data-modal'))
			{
				e.preventDefault();
				var $targetModal = $($(e.target).attr('data-modal'));
				
				$targetModal.find('form input:not([type=submit])').val("");
				$targetModal.find('form textarea').val("");
				$targetModal.find('.modal__error').remove();
				
				$targetModal.trigger("modal-init", e.target);
				
				$targetModal.modal('show');
			}
		});
	}
}

export default Modal;