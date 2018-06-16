import Vue from 'vue';
import $ from 'jquery';
import TableSorting from './TableSorting';
import TableHighlighting from './TableHighlighting';

const eventBus = new Vue();

Vue.component('color-table',
{
	template: '#color-table-template',
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

Vue.component('color-table-row',
{
	template: '#color-table-row-template',
	data: () =>
	{
		return {
			selected: false,
			toSelect: false
		};
	},
	props:
	{
		model: Object,
		initiallySelected: Boolean
	},
	methods:
	{
		clickDefault: function(event)
		{
			var _this = this;
			
			var oldValue = !event.target.checked;
			
			if (confirm("Are you sure you want to change the default background color?"))
			{
				$.ajax
				({
					url: '/colors',
					type: 'POST',
					data: 'color_id=' + (event.target.checked ? _this.model.id : '') + '&action=setDefaultColor',
					success: function(data)
					{
						if (event.target.checked)
						{
							_this.toSelect = true;
						}
						else
						{
							_this.toSelect = false;
						}
						
						eventBus.$emit('select');
					},
					error: function()
					{
						// TODO: Not sure why I had to do this
						$(event.target).prop('checked', oldValue);
					}
				});
			}
			else
			{
				event.preventDefault();
				return false;
			}
		},
		clickDelete: function()
		{
			var _this = this;
			
			if (confirm("Are you sure you want to delete this color?\n\nIf you do, all items that use it will be deleted as well."))
			{
				$.ajax
				({
					url: '/colors',
					type: 'POST',
					data: 'color_id=' + _this.model.id + '&action=delete',
					success: function()
					{
						_this.$emit('delete', _this.model);
						location.reload();
					}
				});
			}
		}
	},
	created: function()
	{
		this.selected = this.initiallySelected;
	},
	mounted: function()
	{
		eventBus.$on('select', () =>
		{
			this.selected = false;
			if (this.toSelect)
			{
				this.selected = true;
				this.toSelect = false;
			}
		});
	}
});
