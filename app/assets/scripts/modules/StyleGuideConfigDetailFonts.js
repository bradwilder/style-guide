import Vue from 'vue';
import $ from 'jquery';
import TableSorting from './TableSorting';
import TableHighlighting from './TableHighlighting';

Vue.component('font-table',
{
	template: '#font-table-template',
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
		deleteItem: function(item)
		{
			var index = this.data.items.indexOf(item);
			if (index != -1)
			{
				this.data.items.splice(index, 1);
			}
		}
	},
	mounted: function()
	{
		new TableSorting();
		new TableHighlighting();
	},
	created: function()
	{
		this.data = this.initialData;
	}
});

Vue.component('font-table-row',
{
	template: '#font-table-row-template',
	props:
	{
		model: Object
	},
	methods:
	{
		clickDelete: function()
		{
			var _this = this;
			
			if (confirm("Are you sure you want to delete this font?\n\nIf you do, all items that use it will be deleted as well."))
			{
				$.ajax
				({
					url: '/fonts',
					type: 'POST',
					data: 'font_id=' + _this.model.id + '&action=delete',
					success: function()
					{
						_this.$emit('delete', _this.model);
						location.reload();
					}
				});
			}
		}
	}
});
