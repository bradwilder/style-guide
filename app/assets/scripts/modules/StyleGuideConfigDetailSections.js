import Vue from 'vue';
import $ from 'jquery';
import TableHighlighting from './TableHighlighting';

Vue.component('sections',
{
	template: '#sections-template',
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
		new TableHighlighting();
	},
	created: function()
	{
		this.data = this.initialData;
	}
});

Vue.component('sections-row',
{
	template: '#sections-row-template',
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
			
			if (confirm("Are you sure you want to " + action + " this section?"))
			{
				$.ajax
				({
					url: '/section',
					type: 'POST',
					data: 'section_id=' + _this.model.id + '&enabled=' + (event.target.checked ? 1 : 0) + '&action=enable',
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
			
			if (confirm("Are you sure you want to delete this section?\n\nIf you do, all subsections and items attached to it will be deleted as well."))
			{
				$.ajax
				({
					url: '/section',
					type: 'POST',
					data: 'section_id=' + _this.model.id + '&action=delete',
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
