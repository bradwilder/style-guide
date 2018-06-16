import Vue from 'vue';

class Modal_EditStyleguideElementItem
{
	constructor()
	{
		this.addUploadModal = $('#addElementItemModal');
		this.events();
	}
	
	events()
	{
		this.addUploadModal.on("modal-init", function(e, trigger)
		{
			var $modal = $(this);
			
			var itemID = $(trigger).attr('data-id');
			
			var selectedUploadIDs = [];
			var uploads;
			
			var itemPromise = new Promise((resolve, reject) =>
			{
				$.ajax
				({
					url: '/item',
					type: 'GET',
					data: 'item_id=' + itemID + '&action=get',
					dataType: 'json',
					success: function(data)
					{
						for (var i = 0; i < data.uploads.length; i++)
						{
							selectedUploadIDs.push(data.uploads[i].id);
						}
						
						resolve();
					}
				});
			});
			
			var uploadsPromise = new Promise((resolve, reject) =>
			{
				$.ajax
				({
					url: '/styleguideConfigDetail',
					type: 'GET',
					data: 'configType=Uploads&configID=null',
					success: function(data)
					{
						data = JSON.parse(data);
						uploads = data.item;
						resolve();
					}
				});
			});
			
			itemPromise.then(() =>
			{
				uploadsPromise.then(() =>
				{
					if (vueInst)
					{
						vueInst.$destroy();
					}
					
					var $modalContainer = $('#addElementItemModalBody');
					$modalContainer.empty();
					$modalContainer.html($('#segmented-element-item-add-modal-template').html());
					
					vueInst = new Vue
					({
						el: "#addElementItemModalBody",
						data:
						{
							itemID: itemID,
							originalUploadIDs: selectedUploadIDs,
							selectedUploadID: null,
							uploads: uploads,
							selectedItem: null
						},
						methods:
						{
							selectItem: function(item)
							{
								this.selectedItem = item;
								this.selectedUploadID = item.id;
							}
						}
					});
				});
			});
		});
	}
}

var vueInst;

export default Modal_EditStyleguideElementItem;

Vue.component('segmented-element-item-add-modal-item',
{
	template: '#segmented-element-item-add-modal-item-template',
	props:
	{
		model: Object,
		selectedItem: Object,
		originalUploadIds: Array
	},
	data: function()
	{
		return {
			open: false
		}
	},
	computed:
	{
		isFolder: function()
		{
			return this.model.folder;
		},
		isSelected: function()
		{
			return this.selectedItem === this.model;
		}
	},
	methods:
	{
		toggle: function()
		{
			if (this.isFolder)
			{
				this.open = !this.open;
			}
		},
		select: function()
		{
			if (!this.isFolder && !this.originalUploadIds.find(x => x == this.model.id))
			{
				this.$emit('select', this.model);
			}
		},
		selectItem: function(item)
		{
			this.$emit('select', item);
		}
	}
});
