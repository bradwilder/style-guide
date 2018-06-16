import Vue from 'vue';
import $ from 'jquery';

const eventBus = new Vue();

Vue.component('uploads',
{
	template: '#uploads-template',
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
	mounted: () =>
	{
		eventBus.$on('deleteItem', (item) =>
		{
			var node = findNode(this.data.item, item.id);
			var index = node.parent.children.indexOf(node.node);
			if (index != -1)
			{
				node.parent.children.splice(index, 1);
			}
		});
	},
	created: function()
	{
		this.data = this.initialData;
	}
});

Vue.component('upload-item',
{
	template: '#upload-item-template',
	props:
	{
		model: Object
	},
	data: function()
	{
		return {
			open: false,
			selected: false,
			toSelect: false
		}
	},
	methods:
	{
		toggle: function()
		{
			if (this.model.folder)
			{
				this.open = !this.open;
			}
		},
		select: function()
		{
			if (!this.selected)
			{
				this.toSelect = true;
				eventBus.$emit('selectNew');
			}
		},
		deleteItem: function()
		{
			var _this = this;
			
			var confirmStr = '';
			if (this.model.folder)
			{
				confirmStr = 'Are you sure you want to delete this folder? If you do, all uploads within it, and any items using those uploads, will be deleted as well.';
			}
			else
			{
				confirmStr = 'Are you sure you want to delete this upload? If you do, any items using it will be deleted as well.';
			}
			
			if (confirm(confirmStr))
			{
				$.ajax
				({
					url: '/upload',
					type: 'POST',
					data: 'upload_id=' + _this.model.id + '&action=delete',
					success: function()
					{
						eventBus.$emit('deleteItem', _this.model);
						location.reload();
					}
				});
			}
		}
	},
	mounted: function()
	{
		eventBus.$on('selectNew', () =>
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

function findNode(tree, id)
{
	var stack = [{node: tree, parent: null}];
	
	while (stack.length > 0)
	{
		var node = stack.pop();
		if (node.node.id == id)
		{
			return node;
		}
		else if (node.node.children && node.node.children.length)
		{
			for (var i = 0; i < node.node.children.length; i++)
			{
				stack.push({node: node.node.children[i], parent: node.node});
			}
		}
	}
}
