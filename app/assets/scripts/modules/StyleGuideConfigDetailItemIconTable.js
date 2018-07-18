import Vue from 'vue';
import $ from 'jquery';
import TableHighlighting from './TableHighlighting';

Vue.component('icon-listing-item',
{
	template: '#icon-listing-item-template',
	data: () =>
	{
		return {
			data: null
		};
	},
	props:
	{
		initialData: Object
	},
	methods:
	{
		clickDelete: function()
		{
			var _this = this;
			
			if (confirm("Are you sure you want to delete this item?"))
			{
				$.ajax
				({
					url: '/item',
					type: 'POST',
					data: 'item_id=' + _this.data.id + '&action=delete',
					success: function()
					{
						location.reload();
					}
				});
			}
		},
		deleteListing: function(item)
		{
			var index = this.data.item.listings.indexOf(item);
			if (index != -1)
			{
				this.data.item.listings.splice(index, 1);
			}
		}
	},
	mounted: function()
	{
		new TableHighlighting();
	},
	created: function()
	{
		this.data = this.initialData;
	}
});

Vue.component('icon-listing-item-row',
{
	template: '#icon-listing-item-row-template',
	props:
	{
		model: Object,
		index: Number,
		itemId: Number
	},
	methods:
	{
		clickDelete: function()
		{
			var _this = this;
			
			if (confirm("Are you sure you want to remove this table listing?"))
			{
				$.ajax
				({
					url: '/iconTableItem',
					type: 'POST',
					data: 'listing_id=' + _this.model.id + '&action=delete',
					success: function()
					{
						_this.$emit('delete', _this.model);
					}
				});
			}
		}
	}
});
