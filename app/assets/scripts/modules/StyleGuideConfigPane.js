import Vue from 'vue';
import $ from 'jquery';
import StyleGuideConfigDetailColors from './StyleGuideConfigDetailColors';
import StyleGuideConfigDetailFonts from './StyleGuideConfigDetailFonts';
import StyleGuideConfigDetailUploads from './StyleGuideConfigDetailUploads';
import StyleGuideConfigDetailSections from './StyleGuideConfigDetailSections';
import StyleGuideConfigDetailSection from './StyleGuideConfigDetailSection';
import StyleGuideConfigDetailSubsection from './StyleGuideConfigDetailSubsection';
import StyleGuideConfigDetailItem from './StyleGuideConfigDetailItem';
import StyleGuideConfigDetailItemColor from './StyleGuideConfigDetailItemColor';
import StyleGuideConfigDetailItemFontFamily from './StyleGuideConfigDetailItemFontFamily';
import StyleGuideConfigDetailItemFontTable from './StyleGuideConfigDetailItemFontTable';
import StyleGuideConfigDetailItemIconTable from './StyleGuideConfigDetailItemIconTable';
import StyleGuideConfigDetailItemElement from './StyleGuideConfigDetailItemElement';
import StyleGuideConfigDetailItemColumns from './StyleGuideConfigDetailItemColumns';

const eventBus = new Vue();

class StyleGuideConfigPane
{
	constructor()
	{
		new Vue
		({
			el: '.sg-config-pane__row',
			data:
			{
				selectedConfigComponent: null,
				selectedItemCode: null,
				selectedData: null
			},
			mounted: function()
			{
				eventBus.$on('selectNew', (options) =>
				{
					this.selectedData = null;
					this.selectedItemCode = null;
					this.selectedConfigComponent = null;
					
					var _this = this;
					
					if (options)
					{
						$.ajax
						({
							url: '/styleguideConfigDetail',
							type: 'GET',
							data: 'configType=' + encodeURIComponent(options.type) + '&configID=' + options.id,
							dataType: 'json',
							success: function(data)
							{
								//debugger;
								
								$('.sg-config-pane__tree').addClass('sg-config-pane--hide');
								$('.sg-config-pane__detail').addClass('sg-config-pane--show');
								$('.sg-config-pane__back-button').addClass('sg-config-pane--show');
								
								_this.selectedData = data;
								
								switch (options.type)
								{
									case 'Colors':
										_this.selectedConfigComponent = 'color-table';
										break;
									case 'Fonts':
										_this.selectedConfigComponent = 'font-table';
										break;
									case 'Uploads':
										_this.selectedConfigComponent = 'uploads';
										break;
									case 'Sections':
										_this.selectedConfigComponent = 'sections';
										break;
									case 'Section':
										_this.selectedConfigComponent = 'my-section';
										break;
									case 'Subsection':
										_this.selectedConfigComponent = 'subsection';
										break;
									case 'Item':
										_this.selectedItemCode = data.type.code;
										_this.selectedConfigComponent = 'config-item';
										break;
								}
							}
						});
					}
				});
			}
		});
		
		this.events();
	}
	
	events()
	{
		$('body').click(function(e)
		{
			if ($(e.target).hasClass('sg-config-pane__back-button'))
			{
				$('.sg-config-pane__tree').removeClass('sg-config-pane--hide');
				$('.sg-config-pane__detail').removeClass('sg-config-pane--show');
				$('.sg-config-pane__back-button').removeClass('sg-config-pane--show');
				
				eventBus.$emit('selectNew', null);
			}
		});
	}
}

export default StyleGuideConfigPane;

Vue.component('config-tree',
{
	template: '#config-tree-template',
	data: function()
	{
		return {
			treeData: []
		};
	},
	mounted: function()
	{
		var _this = this;
		$.ajax
		({
			url: '/styleguideConfig',
			type: 'GET',
			data: 'action=tree',
			dataType: 'json',
			success: function(data)
			{
				_this.treeData = data;
			}
		});
	}
});

Vue.component('config-tree-item',
{
	template: '#config-tree-item-template',
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
				eventBus.$emit('selectNew', {id: this.model.id, type: this.model.type});
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
