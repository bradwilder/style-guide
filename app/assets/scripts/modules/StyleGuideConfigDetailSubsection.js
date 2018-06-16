import Vue from 'vue';
import $ from 'jquery';
import TableHighlighting from './TableHighlighting';

Vue.component('subsection',
{
	template: '#subsection-template',
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
		},
		deleteSub: function(item)
		{
			var index = this.data.items.indexOf(item);
			if (index != -1)
			{
				this.data.subitems.splice(index, 1);
			}
		},
		clickDelete: function()
		{
			var _this = this;
			
			if (confirm("Are you sure you want to delete this subsection?\n\nIf you do, all items attached to it will be deleted as well."))
			{
				$.ajax
				({
					url: '/subsection',
					type: 'POST',
					data: 'subsection_id=' + _this.data.item.id + '&action=delete',
					success: function()
					{
						location.reload();
					}
				});
			}
		},
		clickEnabled: function(event)
		{
			var _this = this;
			
			var oldValue = !event.target.checked;
			var action = event.target.checked ? 'enable' : 'disable';
			
			if (confirm("Are you sure you want to " + action + " this subsection?"))
			{
				$.ajax
				({
					url: '/subsection',
					type: 'POST',
					data: 'subsection_id=' + _this.data.item.id + '&enabled=' + (event.target.checked ? 1 : 0) + '&action=enable',
					success: function()
					{
						location.reload();
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

Vue.component('subsection-items-row',
{
	template: '#subsection-items-row-template',
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
			
			if (confirm("Are you sure you want to delete this item?"))
			{
				$.ajax
				({
					url: '/item',
					type: 'POST',
					data: 'item_id=' + _this.model.id + '&action=delete',
					success: function()
					{
						_this.$emit('deleteitem', _this.model);
						location.reload();
					}
				});
			}
		}
	}
});

Vue.component('subsection-subsubsections-row',
{
	template: '#subsection-subsubsections-row-template',
	props:
	{
		model: Object,
		index: Number
	},
	methods:
	{
		clickEnabled: function(event)
		{
			var _this = this;
			
			var oldValue = !event.target.checked;
			var action = event.target.checked ? 'enable' : 'disable';
			
			if (confirm("Are you sure you want to " + action + " the sub-subsection?"))
			{
				$.ajax
				({
					url: '/subsection',
					type: 'POST',
					data: 'subsection_id=' + _this.model.id + '&enabled=' + (event.target.checked ? 1 : 0) + '&action=enable',
					success: function(data)
					{
						_this.model.enabled = event.target.checked;
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
			
			if (confirm("Are you sure you want to delete this sub-subsection?\n\nIf you do, all items attached to it will be deleted as well."))
			{
				$.ajax
				({
					url: '/subsection',
					type: 'POST',
					data: 'subsection_id=' + _this.model.id + '&action=delete',
					success: function()
					{
						_this.$emit('deletesub', _this.model);
						location.reload();
					}
				});
			}
		}
	}
});
