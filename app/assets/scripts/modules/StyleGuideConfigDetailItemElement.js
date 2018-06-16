import Vue from 'vue';
import $ from 'jquery';
import TableHighlighting from './TableHighlighting';

Vue.component('segmented-element-item',
{
	template: '#segmented-element-item-template',
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
		deleteUpload: function(item)
		{
			var index = this.data.item.images.indexOf(item);
			if (index != -1)
			{
				this.data.item.images.splice(index, 1);
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

Vue.component('segmented-element-item-row',
{
	template: '#segmented-element-item-row-template',
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
			
			if (confirm("Are you sure you want to remove this image?"))
			{
				$.ajax
				({
					url: '/elementItem',
					type: 'POST',
					data: 'upload_id=' + _this.model.id + '&item_id=' + _this.itemId + '&action=deleteUpload',
					success: function()
					{
						_this.$emit('delete', _this.model);
					}
				});
			}
		}
	}
});
