import Vue from 'vue';
import $ from 'jquery';

const eventBus = new Vue();

Vue.component('font-table-item',
{
	template: '#font-table-item-template',
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
		deleteItem: function(item)
		{
			var index = this.data.item.listings.indexOf(item);
			if (index != -1)
			{
				this.data.item.listings.splice(index, 1);
			}
		}
	},
	created: function()
	{
		this.data = this.initialData;
	}
});

Vue.component('font-table-item-row',
{
	template: '#font-table-item-row-template',
	data: () =>
	{
		return {
			selected: false,
			toSelect: false
		};
	},
	props:
	{
		model: Object
	},
	methods:
	{
		select: function()
		{
			if (!this.selected)
			{
				this.toSelect = true;
				eventBus.$emit('selectNew');
			}
		},
		clickDelete: function()
		{
			var _this = this;
			
			if (confirm("Are you sure you want to remove this listing?"))
			{
				$.ajax
				({
					url: '/fontTableItem',
					type: 'POST',
					data: 'listing_id=' + _this.model.id + '&action=deleteListing',
					success: function()
					{
						_this.$emit('delete', _this.model);
					}
				});
			}
		},
		deleteItem: function(item)
		{
			var index = this.model.cssList.indexOf(item);
			if (index != -1)
			{
				this.model.cssList.splice(index, 1);
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

Vue.component('font-table-item-font-row',
{
	template: '#font-table-item-font-row-template',
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
		listingId: Number
	},
	methods:
	{
		select: function()
		{
			if (!this.selected)
			{
				this.toSelect = true;
				eventBus.$emit('selectNew');
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

Vue.component('font-table-item-css-row',
{
	template: '#font-table-item-css-row-template',
	data: () =>
	{
		return {
			selected: false,
			toSelect: false
		};
	},
	props:
	{
		model: Object
	},
	methods:
	{
		select: function()
		{
			if (!this.selected)
			{
				this.toSelect = true;
				eventBus.$emit('selectNew');
			}
		},
		clickDelete: function()
		{
			var _this = this;
			
			if (confirm("Are you sure you want to remove this css?"))
			{
				$.ajax
				({
					url: '/fontTableItem',
					type: 'POST',
					data: 'listing_css_id=' + _this.model.id + '&action=deleteListingCSS',
					success: function()
					{
						_this.$emit('delete', _this.model);
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
