import Vue from 'vue';

class Modal_EditStyleguideIconTableItem
{
	constructor()
	{
		this.editFontModal = $('#editIconTableItemFontModal');
		this.addListingModal = $('#addIconTableItemModal');
		this.editListingModal = $('#editIconTableItemModal');
		this.events();
	}
	
	events()
	{
		this.editFontModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(e.target);
			
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
					
					var $modalContainer = $('#editIconTableItemFontModalBody');
					$modalContainer.empty();
					$modalContainer.html($('#icon-listing-item-edit-font-modal-template').html());
					
					vueInst = new Vue
					({
						el: "#editIconTableItemFontModalBody",
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
		
		this.addListingModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(e.target);
			
			var itemID = $(trigger).attr('data-id');
			
			$modal.find('[name=item_id]').val(itemID);
		});
		
		this.editListingModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(e.target);
			
			var listingID = $(trigger).attr('data-id');
			
			$modal.find('[name=listing_id]').val(listingID);
			
			$.ajax
			({
				url: '/iconTableItem',
				type: 'GET',
				data: 'listing_id=' + listingID + '&action=getListing',
				success: function(data)
				{
					$modal.find('[name=listing]').val(data);
				}
			});
		});
	}
}

var vueInst;

export default Modal_EditStyleguideIconTableItem;
