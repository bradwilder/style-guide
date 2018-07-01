class Modal_User
{
	constructor()
	{
		this.newUserModal = $('#newUserModal');
		this.editUserModal = $('#editUserModal');
		this.deleteUserModal = $('#deleteUserModal');
		this.changePasswordModal = $('#changePasswordModal');
		this.events();
	}
	
	events()
	{
		this.newUserModal.on("modal-init", function(e)
		{
			var $modal = $(e.target);
			
			var $select = $modal.find('[name=group]');
			$select.empty();
			
			$.ajax
			({
				url: '/user',
				type: 'GET',
				data: 'action=groups',
				dataType: 'json',
				success: function(data)
				{
					for (var i = 0; i < data.length; i++)
					{
						var row = data[i];
						$select.append('<option value="' + row["id"] + '">' + row["name"] + '</option>');
					}
					
					$select.find('option').filter(function() {return $(this).html() == "Root";}).remove();
					$select.find('option:first').prop('selected', true);
				}
			});
		});
		
		this.editUserModal.on("modal-init", function(e, target)
		{
			var $modal = $(e.target);
			
			var $selectedRow = $(target).closest('tr');
			var selectedID = $selectedRow.find('.users-table__id').html();
			var selectedEmail = $selectedRow.find('.users-table__email').html();
			var selectedPhone = $selectedRow.find('.users-table__phone').html();
			var selectedDisplayName = $selectedRow.find('.users-table__display-name').html();
			var selectedGroupID = $selectedRow.attr('data-group-id');
			
			$modal.find('[name=id]').val(selectedID);
			
			$modal.find('[name=email]').val(selectedEmail);
			$modal.find('[name=email]').attr('data-except-self', selectedID);
			$modal.find('[name=email]').attr('data-original-value', selectedEmail);
			
			$modal.find('[name=phone]').val(selectedPhone);
			$modal.find('[name=phone]').attr('data-original-value', selectedPhone);
			
			$modal.find('[name=displayName]').val(selectedDisplayName);
			$modal.find('[name=displayName]').attr('data-original-value', selectedDisplayName);
			
			var currentUserID = $('[data-user-id]').attr('data-user-id');
			if (currentUserID != selectedID)
			{
				$modal.find('[name=email]').prop('disabled', true);
				$modal.find('[name=email]').removeClass('iv_inputValidator');
				$modal.find('[name=phone]').prop('disabled', true);
				$modal.find('[name=displayName]').prop('disabled', true);
			}
			else
			{
				$modal.find('[name=email]').prop('disabled', false);
				$modal.find('[name=phone]').prop('disabled', false);
				$modal.find('[name=displayName]').prop('disabled', false);
			}
			
			var $groupSelect = $modal.find('[name=group]');
			$groupSelect.empty();
			
			$.ajax
			({
				url: '/user',
				type: 'GET',
				data: 'action=groups',
				dataType: 'json',
				success: function(data)
				{
					for (var i = 0; i < data.length; i++)
					{
						var row = data[i];
						$groupSelect.append('<option value="' + row["id"] + '">' + row["name"] + '</option>');
					}
					
					$groupSelect.find('option[value=' + selectedGroupID + ']').prop('selected', true);
					$groupSelect.attr('data-original-value', selectedGroupID);
					
					if ($groupSelect.find(':selected').text() == 'Root')
					{
						$groupSelect.prop('disabled', true);
					}
					else
					{
						$groupSelect.prop('disabled', false);
						$groupSelect.find('option').filter(function() {return $(this).html() == "Root";}).remove();
					}
				}
			});
		});
		
		this.deleteUserModal.on("modal-init", function(e, target)
		{
			var $modal = $(e.target);
			
			var $selectedRow = $(target).closest('tr');
			var selectedID = $selectedRow.find('.users-table__id').html();
			var selectedEmail = $selectedRow.find('.users-table__email').html();
			
			$modal.find('[name=user_id_delete]').val(selectedID);
			$modal.find('.delete-user-name').html(selectedEmail);
			
			$modal.find('[name=user_id_current]').val($('[data-user-id]').attr('data-user-id'));
		});
		
		this.changePasswordModal.on("modal-init", function(e, target)
		{
			var $modal = $(e.target);
			
			var $selectedRow = $(target).closest('tr');
			var selectedID = $selectedRow.find('.users-table__id').html();
			
			$modal.find('[name=id]').val(selectedID);
		});
	}
}

export default Modal_User;