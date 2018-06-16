import Vue from 'vue';

class Modal_EditStyleguideItem
{
	constructor()
	{
		this.addItemModal = $('#addItemModal');
		this.editItemModal = $('#editItemModal');
		this.editItemColumnsModal = $('#editItemColumnsModal');
		this.events();
	}
	
	events()
	{
		this.addItemModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			var subsectionID = $(trigger).attr('data-id');
			
			$modal.find('[name=subsection_id]').val(subsectionID);
			
			var $typeSelect = $modal.find('[name=type]');
			$typeSelect.empty();
			
			$.ajax
			({
				url: '/item',
				type: 'GET',
				data: 'action=getTypes',
				dataType: 'json',
				success: function(data)
				{
					for (var i = 0; i < data.length; i++)
					{
						var row = data[i];
						$typeSelect.append('<option value="' + row["code"] + '">' + row["description"] + '</option>');
					}
				}
			});
		});
		
		this.editItemModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			var itemID = $(trigger).attr('data-id');
			
			$modal.find('[name=item_id]').val(itemID);
			$modal.find('[name=name]').attr('data-except-self', itemID);
			
			$.ajax
			({
				url: '/item',
				type: 'GET',
				data: 'item_id=' + itemID + '&action=get',
				dataType: 'json',
				success: function(data)
				{
					$modal.find('[name=name]').val(data.name);
				}
			});
		});
		
		this.editItemColumnsModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			var itemID = $(trigger).attr('data-id');
			
			$modal.find('[name=item_id]').val(itemID);
			
			$.ajax
			({
				url: '/item',
				type: 'GET',
				data: 'item_id=' + itemID + '&action=getColumns',
				dataType: 'json',
				success: function(columnData)
				{
					if (vueInstColumns)
					{
						vueInstColumns.$destroy();
					}
					
					var $modalContainer = $('#editColorItemColumnsModalBody');
					$modalContainer.empty();
					$modalContainer.html($('#columns-modal-template').html());
					
					vueInstColumns = new Vue
					({
						el: "#editColorItemColumnsModalBody",
						data:
						{
							itemID: itemID,
							columns: columnData.columns,
							mins: columnData.mins
						}
					});
				}
			});
		});
	}
}

var vueInstColumns;

export default Modal_EditStyleguideItem;
