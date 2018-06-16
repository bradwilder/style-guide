import Vue from 'vue';

class Modal_EditStyleguideFontFamilyItem
{
	constructor()
	{
		this.editModal = $('#editFontFamilyItemModal');
		this.events();
	}
	
	events()
	{
		this.editModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			var itemID = $(trigger).attr('data-id');
			
			var selectedFontID;
			var fonts;
			
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
						selectedFontID = data.fontID;
						resolve();
					}
				});
			});
			
			var fontsPromise = new Promise((resolve, reject) =>
			{
				$.ajax
				({
					url: '/styleguideConfigDetail',
					type: 'GET',
					data: 'configType=Fonts&configID=null',
					success: function(data)
					{
						data = JSON.parse(data);
						fonts = data.items;
						resolve();
					}
				});
			});
			
			itemPromise.then(() =>
			{
				fontsPromise.then(() =>
				{
					if (vueInst)
					{
						vueInst.$destroy();
					}
					
					var $modalContainer = $('#editFontFamilyItemModalBody');
					$modalContainer.empty();
					$modalContainer.html($('#font-family-item-edit-modal-template').html());
					
					vueInst = new Vue
					({
						el: "#editFontFamilyItemModalBody",
						data:
						{
							itemID: itemID,
							originalFontID: selectedFontID,
							selectedFontID: selectedFontID,
							fonts: fonts
						}
					});
				});
			});
		});
	}
}

var vueInst;

export default Modal_EditStyleguideFontFamilyItem;
