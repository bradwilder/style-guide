import Vue from 'vue';
import $ from 'jquery';
import TableHighlighting from './TableHighlighting';

Vue.component('colors-item',
{
	template: '#colors-item-template',
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
		deleteColor: function(item)
		{
			var index = this.data.item.colors.indexOf(item);
			if (index != -1)
			{
				this.data.item.colors.splice(index, 1);
			}
		},
		deleteDescriptor: function(descriptor)
		{
			var index = this.data.item.descriptors.indexOf(descriptor);
			if (index != -1)
			{
				this.data.item.descriptors.splice(index, 1);
			}
		},
		deleteDescriptors: function()
		{
			var _this = this;
			
			if (confirm("Are you sure you want to remove all the descriptors from this item? Doing so will update the item type as well."))
			{
				$.ajax
				({
					url: '/colorItem',
					type: 'POST',
					data: 'item_id=' + _this.data.id + '&action=deleteDescriptors',
					dataType: 'json',
					success: function(data)
					{
						_this.data.item.descriptors = [];
						_this.data.type = data;
					}
				});
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

Vue.component('colors-item-row',
{
	template: '#colors-item-row-template',
	props:
	{
		model: Object,
		index: Number,
		showVariants: Boolean,
		canDelete: Boolean,
		itemId: Number
	},
	methods:
	{
		clickDelete: function()
		{
			var _this = this;
			
			if (confirm("Are you sure you want to remove this color?"))
			{
				$.ajax
				({
					url: '/colorItem',
					type: 'POST',
					data: 'color_id=' + _this.model.id + '&item_id=' + _this.itemId + '&action=deleteColor',
					success: function()
					{
						_this.$emit('delete', _this.model);
					}
				});
			}
		}
	}
});

Vue.component('colors-item-descriptors-row',
{
	template: '#colors-item-descriptors-row-template',
	props:
	{
		model: Object,
		index: Number
	},
	methods:
	{
		clickDelete: function()
		{
			var _this = this;
			
			if (confirm("Are you sure you want to remove this descriptor?"))
			{
				$.ajax
				({
					url: '/colorItem',
					type: 'POST',
					data: 'descriptor_id=' + _this.model.id + '&action=deleteDescriptor',
					success: function()
					{
						_this.$emit('deletedescriptor', _this.model);
					}
				});
			}
		}
	}
});
