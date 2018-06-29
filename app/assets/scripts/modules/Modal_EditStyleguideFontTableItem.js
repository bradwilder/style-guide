import Vue from 'vue';

class Modal_EditStyleguideFontTableItem
{
	constructor()
	{
		this.addListingModal = $('#addFontTableItemListingModal');
		this.editListingModal = $('#editFontTableItemListingModal');
		this.editFontModal = $('#editFontTableItemFontModal');
		this.addListingCSSModal = $('#addFontTableItemListingCSSModal');
		this.editListingCSSModal = $('#editFontTableItemListingCSSModal');
		this.events();
	}
	
	events()
	{
		this.addListingModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(e.target);
			
			var itemID = $(trigger).attr('data-id');
			
			$modal.find('[name=item_id]').val(itemID);
			
			var $fontSelect = $modal.find('[name=font_id]');
			$fontSelect.empty();
			
			$.ajax
			({
				url: '/styleguideConfigDetail',
				type: 'GET',
				data: 'configType=Fonts&configID=null',
				success: function(data)
				{
					data = JSON.parse(data);
					
					for (var i = 0; i < data.items.length; i++)
					{
						var row = data.items[i];
						$fontSelect.append('<option value="' + row["id"] + '">' + row["name"] + '</option>');
					}
				}
			});
		});
		
		this.editListingModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(e.target);
			
			var listingID = $(trigger).attr('data-id');
			
			$modal.find('[name=listing_id]').val(listingID);
			
			$.ajax
			({
				url: '/fontTableItem',
				type: 'GET',
				data: 'listing_id=' + listingID + '&action=getListing',
				success: function(data)
				{
					$modal.find('[name=listing]').val(data);
				}
			});
		});
		
		this.editFontModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(e.target);
			
			var listingID = $(trigger).attr('data-id');
			
			$modal.find('[name=listing_id]').val(listingID);
			
			var $fontSelect = $modal.find('[name=font_id]');
			$fontSelect.empty();
			
			$.ajax
			({
				url: '/styleguideConfigDetail',
				type: 'GET',
				data: 'configType=Fonts&configID=null',
				success: function(data)
				{
					data = JSON.parse(data);
					
					for (var i = 0; i < data.items.length; i++)
					{
						var row = data.items[i];
						$fontSelect.append('<option value="' + row["id"] + '">' + row["name"] + '</option>');
					}
				}
			});
		});
		
		this.addListingCSSModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(e.target);
			
			var listingID = $(trigger).attr('data-id');
			
			$modal.find('[name=listing_id]').val(listingID);
		});
		
		this.editListingCSSModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(e.target);
			
			var listingCSSID = $(trigger).attr('data-id');
			
			$modal.find('[name=listing_css_id]').val(listingCSSID);
			
			$.ajax
			({
				url: '/fontTableItem',
				type: 'GET',
				data: 'listing_css_id=' + listingCSSID + '&action=getListingCSS',
				success: function(data)
				{
					$modal.find('[name=css]').val(data);
				}
			});
		});
	}
}

var vueInst;

export default Modal_EditStyleguideFontTableItem;
