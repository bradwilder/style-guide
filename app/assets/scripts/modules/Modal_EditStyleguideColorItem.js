import Vue from 'vue';

class Modal_EditStyleguideColorItem
{
	constructor()
	{
		this.editColorModal = $('#editColorItemModal');
		this.addColorModal = $('#addColorItemModal');
		this.editColorDescriptorModal = $('#editColorItemDescriptorModal');
		this.addColorDescriptorModal = $('#addColorItemDescriptorModal');
		this.events();
	}
	
	events()
	{
		this.editColorModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			var itemID = $(trigger).attr('data-id');
			var selectedColorID;
			var useVariants;
			var colors;
			
			var itemPromise = new Promise((resolve, reject) =>
			{
				$.ajax
				({
					url: '/item',
					type: 'GET',
					data: 'item_id=' + itemID + '&action=get',
					dataType: 'json',
					success: function(data)
					{
						selectedColorID = data.colors[0].id;
						useVariants = data.type.code.indexOf('var') != -1;
						resolve();
					}
				});
			});
			
			var colorsPromise = new Promise((resolve, reject) =>
			{
				$.ajax
				({
					url: '/styleguideConfigDetail',
					type: 'GET',
					data: 'configType=Colors&configID=null',
					success: function(data)
					{
						data = JSON.parse(data);
						colors = data.items;
						resolve();
					}
				});
			});
			
			itemPromise.then(() =>
			{
				colorsPromise.then(() =>
				{
					if (vueInstEditColor)
					{
						vueInstEditColor.$destroy();
					}
					
					var $modalContainer = $('#editColorItemModalBody');
					$modalContainer.empty();
					$modalContainer.html($('#colors-item-edit-modal-template').html());
					
					vueInstEditColor = new Vue
					({
						el: "#editColorItemModalBody",
						data:
						{
							itemID: itemID,
							originalColorID: selectedColorID,
							selectedColorID: selectedColorID,
							colors: colors,
							originalUseVariants: useVariants,
							useVariants: useVariants
						},
						computed:
						{
							showVariants: function()
							{
								return this.colors.filter(x => x.id == this.selectedColorID)[0].variant1 ? true : false;
							}
						},
						methods:
						{
							colorSelect: function()
							{
								this.useVariants = this.useVariants ? this.showVariants : false;
							}
						}
					});
				});
			});
		});
		
		this.addColorModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			var itemID = $(trigger).attr('data-id');
			
			var selectedColorIDs = [];
			var colors;
			
			var itemPromise = new Promise((resolve, reject) =>
			{
				$.ajax
				({
					url: '/item',
					type: 'GET',
					data: 'item_id=' + itemID + '&action=get',
					dataType: 'json',
					success: function(data)
					{
						for (var i = 0; i < data.colors.length; i++)
						{
							selectedColorIDs.push(data.colors[i].id);
						}
						
						resolve();
					}
				});
			});
			
			var colorsPromise = new Promise((resolve, reject) =>
			{
				$.ajax
				({
					url: '/styleguideConfigDetail',
					type: 'GET',
					data: 'configType=Colors&configID=null',
					success: function(data)
					{
						data = JSON.parse(data);
						colors = data.items;
						resolve();
					}
				});
			});
			
			itemPromise.then(() =>
			{
				colorsPromise.then(() =>
				{
					if (vueInstAddColor)
					{
						vueInstAddColor.$destroy();
					}
					
					var $modalContainer = $('#addColorItemModalBody');
					$modalContainer.empty();
					$modalContainer.html($('#colors-item-add-modal-template').html());
					
					vueInstAddColor = new Vue
					({
						el: "#addColorItemModalBody",
						data:
						{
							itemID: itemID,
							originalColorIDs: selectedColorIDs,
							selectedColorID: null,
							colors: colors
						}
					});
				});
			});
		});
		
		this.editColorDescriptorModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			var descriptorID = $(trigger).attr('data-id');
			
			$modal.find('[name=descriptor_id]').val(descriptorID);
			
			$.ajax
			({
				url: '/colorItem',
				type: 'GET',
				data: 'descriptor_id=' + descriptorID + '&action=getDescriptor',
				dataType: 'json',
				success: function(data)
				{
					$modal.find('[name=descriptor]').val(data);
				}
			});
		});
		
		this.addColorDescriptorModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			var itemID = $(trigger).attr('data-id');
			
			$modal.find('[name=item_id]').val(itemID);
		});
	}
}

var vueInstEditColor;
var vueInstAddColor;

export default Modal_EditStyleguideColorItem;
