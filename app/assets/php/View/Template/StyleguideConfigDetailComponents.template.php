<script type="text/x-template" id="config-tree-template">
	<div class="sg-config-tree">
		<ul class="sg-config-tree__list">
			<config-tree-item v-for="item in treeData.children" :key="item.id" :model="item"></config-tree-item>
		</ul>
	</div>
</script>

<script type="text/x-template" id="config-tree-item-template">
	<li class="sg-config-tree__list-item type__title" :class="{'type__title--darker': model.enabled}">
		<a class="sg-config-tree__link" :class="{'sg-config-tree__link--active': selected}" href="#" @click="select">{{ model.name }}</a>
		<ul v-if="model.children.length > 0" class="sg-config-tree__list">
			<config-tree-item v-for="model in model.children" :key="model.id" :model="model"></config-tree-item>
		</ul>
	</li>
</script>

<script type="text/x-template" id="color-table-template">
	<div>
		<div class="sg-config-detail__header">
			<span class="sg-config-detail__header-buttons">
				<i class="sg-config-detail__header-button sg-config-detail__header-button--dim fa fa-plus" data-modal="#addColorModal"></i>
			</span>
			<h3 class="sg-config-detail__title type__title">Colors</h3>
		</div>
		
		<div class="sg-config-detail__body">
			<div class="sg-config-detail__table-wrapper">
				<?php
					$tableSortingOptions = new TableSortingOptions();
					$tableModel = new TableModel($tableSortingOptions, true, true, '<tr is="color-table-row" v-for="item in data.items" :model="item" :initially-selected="item.id == data.item" v-on:delete="deleteItem"></tr>');
					
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Name', null, true, true));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('#', null, true, true));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Variant 1', null, true, true));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Variant 2', null, true, true));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('BG', null, true, false));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel(null, null, true, false, true));
					
					$view = new TableView($tableModel, $currentUser);
					echo $view->output();
				?>
			</div>
		</div>
	</div>
</script>

<script type="text/x-template" id="color-table-row-template">
	<tr :data-id="model.id">
		<td>{{model.name}}</td>
		<td>{{model.hex}} <div :style="{'background-color': '#' + model.hex}" class="sg-config-detail__color"></div></td>
		<td>{{model.variant1}} <div :style="{'background-color': '#' + model.variant1}" class="sg-config-detail__color"></div></td>
		<td>{{model.variant2}} <div :style="{'background-color': '#' + model.variant2}" class="sg-config-detail__color"></div></td>
		<td class="tables__checkbox">
			<i v-show="selected" class="sg-config-detail__display tables__display-button fa fa-check"></i>
			<input class="tables__edit-button" type="checkbox" :checked="selected" @click="clickDefault">
		</td>
		<td>
			<i class="tables__edit-button fa fa-pencil" data-modal="#editColorModal"></i>
			<i class="tables__edit-button fa fa-trash" @click="clickDelete"></i>
		</td>
	</tr>
</script>

<script type="text/x-template" id="font-table-template">
	<div>
		<div class="sg-config-detail__header">
			<span class="sg-config-detail__header-buttons">
				<i class="sg-config-detail__header-button sg-config-detail__header-button--dim fa fa-plus" data-modal="#addFontModal"></i>
			</span>
			<h3 class="sg-config-detail__title type__title">Fonts</h3>
		</div>
		
		<div class="sg-config-detail__body">
			<div class="sg-config-detail__table-wrapper">
				<?php
					$tableSortingOptions = new TableSortingOptions();
					$tableModel = new TableModel($tableSortingOptions, true, true, '<tr is="font-table-row" v-for="item in data.items" :model="item"></tr>');
					
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Name', null, true, true));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Type', null, true, true));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel(null, null, true, false, true));
					
					$view = new TableView($tableModel, $currentUser);
					echo $view->output();
				?>
			</div>
		</div>
	</div>
</script>

<script type="text/x-template" id="font-table-row-template">
	<tr :data-id="model.id">
		<td>{{model.name}}</td>
		<td>{{model.type.description}}</td>
		<td>
			<i class="tables__edit-button fa fa-eye" data-modal="#viewFontModal"></i>
			<i class="tables__edit-button fa fa-pencil" data-modal="#editFontModal"></i>
			<i class="tables__edit-button fa fa-trash" @click="clickDelete"></i>
		</td>
	</tr>
</script>

<script type="text/x-template" id="uploads-template">
	<div>
		<h3 class="sg-config-detail__title type__title">Uploads</h3>
		<div class="sg-config-detail__body">
			<ul class="sg-config-uploads sg-config-uploads__list">
				<upload-item :model="data.item"></upload-item>
			</ul>
		</div>
	</div>
</script>

<script type="text/x-template" id="upload-item-template">
	<li>
		<a class="sg-config-uploads__item" :class="{'sg-config-uploads__folder': model.folder, 'sg-config-uploads__file': !model.folder, 'sg-config-uploads__item--active': selected}">
			<span v-if="model.folder" class="fa sg-config-uploads__folder__icon" :class="{'sg-config-uploads__folder__icon--open': open, 'sg-config-uploads__folder__icon--closed': !open}" @click="toggle"></span>
			<span @click="select">{{model.name}}</span>
			<span class="sg-config-uploads__item__options" v-show="selected">
				<span v-if="model.folder">
					<i v-if="model.id" class="fa fa-pencil sg-config-uploads__item__option" data-modal="#editFolderModal" :data-id="model.id" :data-name="model.name"></i>
					<i class="fa fa-folder-o sg-config-uploads__item__option" data-modal="#addFolderModal" :data-id="model.id"></i>
					<i class="fa fa-file-o sg-config-uploads__item__option" data-modal="#addFileModal" :data-id="model.id"></i>
					<i v-if="model.id" class="fa fa-trash-o sg-config-uploads__item__option" @click="deleteItem"></i>
				</span>
				<span v-else>
					<i class="fa fa-eye sg-config-uploads__item__option" data-modal="#viewFileModal" :data-id="model.id"></i>
					<i class="fa fa-pencil sg-config-uploads__item__option" data-modal="#editFileModal" :data-id="model.id"></i>
					<i class="fa fa-trash-o sg-config-uploads__item__option" @click="deleteItem"></i>
				</span>
			</span>
		</a>
		<ul v-if="model.folder" v-show="open" class="sg-config-uploads__list">
			<upload-item v-for="model in model.children" :model="model"></upload-item>
		</ul>
	</li>
</script>

<script type="text/x-template" id="sections-template">
	<div>
		<div class="sg-config-detail__header">
			<span class="sg-config-detail__header-buttons">
				<i class="sg-config-detail__header-button sg-config-detail__header-button--dim fa fa-arrows" data-modal="#arrangeSectionsModal"></i>
				<i class="sg-config-detail__header-button sg-config-detail__header-button--dim fa fa-plus" data-modal="#addSectionModal"></i>
			</span>
			<h3 class="sg-config-detail__title type__title">Sections</h3>
		</div>
		
		<div class="sg-config-detail__body">
			<div class="sg-config-detail__table-wrapper">
				<?php
					$tableModel = new TableModel(null, true, true, '<tr is="sections-row" v-for="(item, index) in data.items" :model="item" :index="index" v-on:delete="deleteItem"></tr>');
					
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Name'));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Enabled'));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel(null, null, false, false, true));
					
					$view = new TableView($tableModel, $currentUser);
					echo $view->output();
				?>
			</div>
		</div>
	</div>
</script>

<script type="text/x-template" id="sections-row-template">
	<tr :data-id="model.id" :class="{odd: index % 2 == 0, even: index % 2 != 0}">
		<td>{{model.name}}</td>
		<td class="tables__checkbox">
			<i v-show="model.enabled" class="sg-config-detail__display tables__display-button fa fa-check"></i>
			<input class="tables__edit-button" type="checkbox" :checked="model.enabled" @click="clickEnabled">
		</td>
		<td>
			<!--<i class="tables__edit-button fa fa-pencil" data-modal="#editSectionModal"></i>-->
			<i v-if="model.userCreated" class="tables__edit-button fa fa-trash" @click="clickDelete"></i>
		</td>
	</tr>
</script>

<script type="text/x-template" id="section-template">
	<div>
		<div class="sg-config-detail__header">
			<span class="sg-config-detail__header-buttons">
				<label class="toggle-switch-custom sg-config-detail__header-button sg-config-detail__switch">
					<input type="checkbox" class="toggle-switch-custom__input" :checked="data.item.enabled" @click="clickEnabled">
					<div class="toggle-switch-custom__slider"></div>
				</label>
				<i class="sg-config-detail__header-button sg-config-detail__header-button--dim fa fa-pencil" data-modal="#editSectionModal" :data-id="data.item.id"></i>
				<i v-if="data.item.userCreated" class="sg-config-detail__header-button sg-config-detail__header-button--dim fa fa-trash" @click="clickDelete"></i>
			</span>
			<h3 class="sg-config-detail__title type__title">{{data.item.name}}</h3>
		</div>
		
		<div class="sg-config-detail__body">
			<section class="editable-section">
				<div class="sg-config-detail__header">
					<span class="sg-config-detail__header-buttons__sub editable-section__button-container">
						<a v-if="data.items.length > 1" tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-arrows" data-modal="#arrangeSubsectionsModal" :data-id="data.item.id"></a>
						<a tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-plus" data-modal="#addSubsectionModal" :data-section-id="data.item.id"></a>
					</span>
					<h4 class="sg-config-detail__desc type__title type__title--darker">Subsections</h4>
				</div>
				
				<div class="sg-config-detail__table-wrapper">
					<?php
						$tableModel = new TableModel(null, true, true, '<tr is="section-row" v-for="(item, index) in data.items" :model="item" :index="index" v-on:delete="deleteItem"></tr>');
						
						$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Name'));
						$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Enabled'));
						$tableModel->addTableHeaderColumn(new TableHeaderColumnModel(null, null, false, false, true));
						
						$view = new TableView($tableModel, $currentUser);
						echo $view->output();
					?>
				</div>
			</section>
		</div>
	</div>
</script>

<script type="text/x-template" id="section-row-template">
	<tr :data-id="model.id" :class="{odd: index % 2 == 0, even: index % 2 != 0}">
		<td>{{model.name}}</td>
		<td class="tables__checkbox">
			<i v-show="model.enabled" class="sg-config-detail__display tables__display-button fa fa-check"></i>
			<input class="tables__edit-button" type="checkbox" :checked="model.enabled" @click="clickEnabled">
		</td>
		<td>
			<!--<i class="tables__edit-button fa fa-pencil" data-modal="#editSectionModal"></i>-->
			<i class="tables__edit-button fa fa-trash" @click="clickDelete"></i>
		</td>
	</tr>
</script>

<script type="text/x-template" id="subsection-template">
	<div>
		<div class="sg-config-detail__header">
			<span class="sg-config-detail__header-buttons">
				<label class="toggle-switch-custom sg-config-detail__header-button sg-config-detail__switch">
					<input type="checkbox" class="toggle-switch-custom__input" :checked="data.item.enabled" @click="clickEnabled">
					<div class="toggle-switch-custom__slider"></div>
				</label>
				<i class="sg-config-detail__header-button sg-config-detail__header-button--dim fa fa-pencil" data-modal="#editSubsectionModal" :data-id="data.item.id"></i>
				<i class="sg-config-detail__header-button sg-config-detail__header-button--dim fa fa-trash" @click="clickDelete"></i>
			</span>
			<h3 class="sg-config-detail__title type__title">{{data.item.name}}</h3>
		</div>
		
		<div class="sg-config-detail__body">
			<h4 class="sg-config-detail__desc type__title"><span class="type__title--darker">Description</span><span class="sg-config-detail__desc__text" v-html="data.item.description"></span></h4>
			
			<section class="editable-section">
				<div class="sg-config-detail__header">
					<span class="sg-config-detail__header-buttons__sub editable-section__button-container">
						<a v-if="data.items.length > 1" tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-arrows" data-modal="#arrangeItemsModal" :data-id="data.item.id"></a>
						<a tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-plus" data-modal="#addItemModal" :data-id="data.item.id"></a>
					</span>
					<h4 class="sg-config-detail__desc type__title type__title--darker">Items</h4>
				</div>
				
				<div class="sg-config-detail__table-wrapper">
					<?php
						$tableModel = new TableModel(null, true, true, '<tr is="subsection-items-row" v-for="(item, index) in data.items" :model="item" :index="index"></tr>');
						
						$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Name'));
						$tableModel->addTableHeaderColumn(new TableHeaderColumnModel(null, null, false, false, true));
						
						$view = new TableView($tableModel, $currentUser);
						echo $view->output();
					?>
				</div>
			</section>
			
			<section v-if="!data.item.parentSubsectionID" class="editable-section">
				<div class="sg-config-detail__header">
					<span class="sg-config-detail__header-buttons__sub editable-section__button-container">
						<a v-if="data.subitems.length > 1" tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-arrows" data-modal="#arrangeSubSubsectionsModal" :data-id="data.item.id"></a>
						<a tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-plus" data-modal="#addSubsectionModal" :data-section-id="data.item.sectionID" :data-parent-sub-id="data.item.id"></a>
					</span>
					<h4 class="sg-config-detail__desc type__title type__title--darker">Sub-subsections</h4>
				</div>
				
				<div class="sg-config-detail__table-wrapper">
					<?php
						$tableModel = new TableModel(null, true, true, '<tr is="subsection-subsubsections-row" v-for="(item, index) in data.subitems" :model="item" :index="index" v-on:deletesub="deleteSub" v-on:deleteitem="deleteItem"></tr>');
						
						$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Name'));
						$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Enabled'));
						$tableModel->addTableHeaderColumn(new TableHeaderColumnModel(null, null, false, false, true));
						
						$view = new TableView($tableModel, $currentUser);
						echo $view->output();
					?>
				</div>
			</section>
		</div>
	</div>
</script>

<script type="text/x-template" id="subsection-subsubsections-row-template">
	<tr :data-id="model.id" :class="{odd: index % 2 == 0, even: index % 2 != 0}">
		<td>{{model.name}}</td>
		<td class="tables__checkbox">
			<i v-show="model.enabled" class="sg-config-detail__display tables__display-button fa fa-check"></i>
			<input class="tables__edit-button" type="checkbox" :checked="model.enabled" @click="clickEnabled">
		</td>
		<td>
			<!--<i class="tables__edit-button fa fa-pencil" data-modal="#editSubsectionModal"></i>-->
			<i class="tables__edit-button fa fa-trash" @click="clickDelete"></i>
		</td>
	</tr>
</script>

<script type="text/x-template" id="subsection-items-row-template">
	<tr :data-id="model.id" :class="{odd: index % 2 == 0, even: index % 2 != 0}">
		<td>{{model.name}}</td>
		<td>
			<!--<i class="tables__edit-button fa fa-pencil" data-modal="#editItemModal"></i>-->
			<i class="tables__edit-button fa fa-trash" @click="clickDelete"></i>
		</td>
	</tr>
</script>

<script type="text/x-template" id="config-item-template">
	<div>
		<div class="sg-config-detail__header">
			<span class="sg-config-detail__header-buttons">
				<i class="sg-config-detail__header-button sg-config-detail__header-button--dim fa fa-pencil" :data-id="initialData.id" data-modal="#editItemModal"></i>
				<i class="sg-config-detail__header-button sg-config-detail__header-button--dim fa fa-trash" @click="clickDelete"></i>
			</span>
			<h3 class="sg-config-detail__title type__title">{{initialData.name}}</h3>
		</div>
		
		<div class="sg-config-detail__body">
			<h4 class="sg-config-detail__desc type__title"><span class="type__title--darker">Item Type</span><span class="sg-config-detail__desc__text">{{initialData.type.description}}</span></h4>
			
			<component :is="selectedItemComponent" :initial-data="initialData"></component>
			
			<columns :model="initialData.columns" :item-id="initialData.id"></columns>
		</div>
	</div>
</script>

<script type="text/x-template" id="colors-item-template">
	<div>
		<section class="editable-section">
			<div class="sg-config-detail__header">
				<span class="sg-config-detail__header-buttons__sub editable-section__button-container">
					<a v-show="data.type.code != 'color-pal' && data.item.colors.length == 1" tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-pencil" data-modal="#editColorItemModal" :data-id="data.id"></a>
					<a v-show="data.type.code == 'color-pal' && data.item.colors.length > 1" tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-arrows" data-modal="#arrangeColorItemModal" :data-id="data.id"></a>
					<a v-show="(data.type.code == 'color-pal' && data.item.colors.length < 6) || (data.type.code != 'color-pal' && data.item.colors.length == 0)" tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-plus" data-modal="#addColorItemModal" :data-id="data.id"></a>
				</span>
				<h4 class="sg-config-detail__desc type__title type__title--darker">Colors</h4>
			</div>
			
			<div class="sg-config-detail__table-wrapper">
				<?php
					$tableModel = new TableModel(null, true, false, '<tr is="colors-item-row" v-for="(item, index) in data.item.colors" :model="item" :index="index" :show-variants="data.type.code.indexOf(`var`) != -1" :can-delete="data.type.code == `color-pal`" :item-id="data.id" v-on:delete="deleteColor"></tr>', null, `:class="{'tables--selectable': data.type.code == 'color-pal'}`);
					
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Name'));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('#'));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Variant 1', 'v-show="data.type.code.indexOf(`var`) != -1"'));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Variant 2', 'v-show="data.type.code.indexOf(`var`) != -1"'));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel(null, 'v-show="data.type.code == `color-pal`"', false, false, true));
					
					$view = new TableView($tableModel, $currentUser);
					echo $view->output();
				?>
			</div>
		</section>
		
		<section v-if="data.type.code.indexOf('pal') == -1" class="editable-section">
			<div class="sg-config-detail__header">
				<span class="sg-config-detail__header-buttons__sub editable-section__button-container">
					<a v-show="data.item.descriptors.length > 1" tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-arrows" data-modal="#arrangeColorItemDescriptorsModal" :data-id="data.id"></a>
					<a tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-plus" data-modal="#addColorItemDescriptorModal" :data-id="data.id"></a>
					<a v-show="data.type.code.indexOf('desc') != -1" tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-trash" @click="deleteDescriptors"></a>
				</span>
				<h4 class="sg-config-detail__desc type__title type__title--darker">Descriptors</h4>
			</div>
			
			<div class="sg-config-detail__table-wrapper">
				<?php
					$tableModel = new TableModel(null, true, true, '<tr is="colors-item-descriptors-row" v-for="(item, index) in data.item.descriptors" :model="item" :index="index" v-on:deletedescriptor="deleteDescriptor"></tr>');
					
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Descriptor'));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel(null, null, false, false, true));
					
					$view = new TableView($tableModel, $currentUser);
					echo $view->output();
				?>
			</div>
		</section>
	</div>
</script>

<script type="text/x-template" id="colors-item-row-template">
	<tr :data-id="model.id" :class="{odd: index % 2 == 0, even: index % 2 != 0}">
		<td>{{model.name}}</td>
		<td>{{model.hex}} <span :style="{'background-color': '#' + model.hex}" class="sg-config-detail__color"></span></td>
		<td v-show="showVariants">{{model.variant1}} <span :style="{'background-color': '#' + model.variant1}" class="sg-config-detail__color"></span></td>
		<td v-show="showVariants">{{model.variant2}} <span :style="{'background-color': '#' + model.variant2}" class="sg-config-detail__color"></span></td>
		<td v-show="canDelete">
			<i class="tables__edit-button fa fa-trash" @click="clickDelete"></i>
		</td>
	</tr>
</script>

<script type="text/x-template" id="colors-item-descriptors-row-template">
	<tr :data-id="model.id" :class="{odd: index % 2 == 0, even: index % 2 != 0}">
		<td v-html="model.description"></td>
		<td>
			<i class="tables__edit-button fa fa-pencil" data-modal="#editColorItemDescriptorModal" :data-id="model.id"></i>
			<i class="tables__edit-button fa fa-trash" @click="clickDelete"></i>
		</td>
	</tr>
</script>

<script type="text/x-template" id="colors-item-edit-modal-template">
	<form data-url="/colorItem" data-action="editColor" method="post" role="form">
		<input :value="itemID" type="text" name="item_id" style="display: none;">
		<div class="modal-body">
			<div class="form-group">
				<label class="sr-only" for="color_id">The color</label>
				<select v-model="selectedColorID" v-show="colors.length > 0" @change="colorSelect" name="color_id" id="color_id">
					<option v-for="color in colors" :value="color.id" :disabled="color.id == originalColorID">{{color.name}}</option>
				</select>
			</div>
			<div v-show="showVariants" class="form-group">
				<label for="use_variants">Use Variants
					<input type="checkbox" name="use_variants" id="use_variants" v-model="useVariants"/>
				</label>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<input v-show="originalColorID != selectedColorID || originalUseVariants != useVariants" type="submit" name="submit" class="btn btn-primary" value="Save"/>
		</div>
	</form>
</script>

<script type="text/x-template" id="colors-item-add-modal-template">
	<form data-url="/colorItem" data-action="addColor" method="post" role="form">
		<input :value="itemID" type="text" name="item_id" style="display: none;">
		<div class="modal-body">
			<div class="form-group">
				<label class="sr-only" for="color_id">The color</label>
				<select v-model="selectedColorID" v-show="colors.length > 0" name="color_id" id="color_id">
					<option v-for="color in colors" :value="color.id" :disabled="originalColorIDs.find(x => x == color.id)">{{color.name}}</option>
				</select>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<input v-show="selectedColorID && !originalColorIDs.find(x => x == selectedColorID)" type="submit" name="submit" class="btn btn-primary" value="Save"/>
		</div>
	</form>
</script>

<script type="text/x-template" id="font-family-item-template">
	<div>
		<section class="editable-section">
			<div class="sg-config-detail__header">
				<span class="sg-config-detail__header-buttons__sub editable-section__button-container">
					<a tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-pencil" data-modal="#editFontFamilyItemModal" :data-id="data.id"></a>
				</span>
				<h4 class="sg-config-detail__desc type__title"><span class="type__title--darker">Font Family</span><span class="sg-config-detail__desc__text">{{data.item.name}}</span></h4>
			</div>
		</section>
		
		<h4 class="sg-config-detail__desc type__title"><span class="type__title--darker">Alphabet</span><span class="sg-config-detail__desc__text">{{data.item.alphabet}}</span></h4>
	</div>
</script>

<script type="text/x-template" id="font-family-item-edit-modal-template">
	<form data-url="/fontFamilyItem" data-action="edit" method="post" role="form" enctype="multipart/form-data">
		<div class="modal-body">
			<input :value="itemID" type="text" name="item_id" style="display: none;">
			<div class="form-group">
				<label class="sr-only" for="font_id">The font</label>
				<select v-model="selectedFontID" v-show="fonts.length > 0" name="font_id" id="font_id">
					<option v-for="font in fonts" :value="font.id" :disabled="font.id == originalFontID">{{font.name}}</option>
				</select>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<input v-show="originalFontID != selectedFontID" type="submit" name="submit" class="btn btn-primary" value="Save"/>
		</div>
	</form>
</script>

<script type="text/x-template" id="font-table-item-template">
	<section class="editable-section">
		<div class="sg-config-detail__header">
			<span class="sg-config-detail__header-buttons__sub editable-section__button-container">
				<a tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-arrows" data-modal="#arrangeFontTableItemListingsModal" :data-id="data.id"></a>
				<a tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-plus" data-modal="#addFontTableItemListingModal" :data-id="data.id"></a>
			</span>
			<h4 class="sg-config-detail__desc type__title type__title--darker">Font Table Items</h4>
		</div>
		
		<ul class="sg-config-font-table-list type__label">
			<font-table-item-row v-for="item in data.item.listings" :model="item" v-on:delete="deleteItem"></font-table-item-row>
		</ul>
	</section>
</script>

<script type="text/x-template" id="font-table-item-row-template">
	<li>
		<a class="sg-config-font-table-list__item" :class="{'sg-config-font-table-list__item--active': selected}">
			<span @click="select">{{model.text}}</span>
			<span v-show="selected" class="sg-config-font-table-list__item__options">
				<span>
					<i class="fa fa-pencil sg-config-font-table-list__item__option" data-modal="#editFontTableItemListingModal" :data-id="model.id"></i>
					<i class="fa fa-plus sg-config-font-table-list__item__option" data-modal="#addFontTableItemListingCSSModal" :data-id="model.id"></i>
					<i class="fa fa-trash sg-config-font-table-list__item__option" @click="clickDelete" :data-id="model.id"></i>
				</span>
			</span>
		</a>
		<ul class="sg-config-font-table-list">
			<font-table-item-font-row :model="model.font" :listing-id="model.id"></font-table-item-font-row>
			<font-table-item-css-row v-for="item in model.cssList" :model="item" v-on:delete="deleteItem"></font-table-item-css-row>
		</ul>
	</li>
</script>

<script type="text/x-template" id="font-table-item-font-row-template">
	<li>
		<a class="sg-config-font-table-list__item" :class="{'sg-config-font-table-list__item--active': selected}">
			<span @click="select">font-family: {{model.name}};</span>
			<span v-show="selected" class="sg-config-font-table-list__item__options">
				<span>
					<i class="fa fa-pencil sg-config-font-table-list__item__option" data-modal="#editFontTableItemFontModal" :data-id="listingId"></i>
				</span>
			</span>
		</a>
	</li>
</script>

<script type="text/x-template" id="font-table-item-css-row-template">
	<li>
		<a class="sg-config-font-table-list__item" :class="{'sg-config-font-table-list__item--active': selected}">
			<span @click="select">{{model.css}}</span>
			<span v-show="selected" class="sg-config-font-table-list__item__options">
				<span>
					<i class="fa fa-pencil sg-config-font-table-list__item__option" data-modal="#editFontTableItemListingCSSModal" :data-id="model.id"></i>
					<i class="fa fa-trash sg-config-font-table-list__item__option" @click="clickDelete" :data-id="model.id"></i>
				</span>
			</span>
		</a>
	</li>
</script>

<script type="text/x-template" id="icon-listing-item-template">
	<div>
		<section class="editable-section">
			<div class="sg-config-detail__header">
				<span class="sg-config-detail__header-buttons__sub editable-section__button-container">
					<a tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-pencil" data-modal="#editIconTableItemFontModal" :data-id="data.id"></a>
				</span>
				<h4 class="sg-config-detail__desc type__title"><span class="type__title--darker">Icon Set</span><span class="sg-config-detail__desc__text">{{data.item.iconSet.name}}</span></h4>
			</div>
		</section>
		
		<section class="editable-section">
			<div class="sg-config-detail__header">
				<span class="sg-config-detail__header-buttons__sub editable-section__button-container">
					<a v-show="data.item.listings.length > 1" tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-arrows" data-modal="#arrangeIconTableItemModal" :data-id="data.id"></a>
					<a tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-plus" data-modal="#addIconTableItemModal" :data-id="data.id"></a>
				</span>
				<h4 class="sg-config-detail__desc type__title type__title--darker">Icons</h4>
			</div>
			
			<div class="sg-config-detail__table-wrapper">
				<?php
					$tableModel = new TableModel(null, true, true, '<tr is="icon-listing-item-row" v-for="(item, index) in data.item.listings" :model="item" :index="index" :item-id="data.id" v-on:delete="deleteListing"></tr>');
					
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('HTML'));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Icon'));
					$tableModel->addTableHeaderColumn(new TableHeaderColumnModel(null, null, false, false, true));
					
					$view = new TableView($tableModel, $currentUser);
					echo $view->output();
				?>
			</div>
		</section>
	</div>
</script>

<script type="text/x-template" id="icon-listing-item-row-template">
	<tr :class="{odd: index % 2 == 0, even: index % 2 != 0}">
		<td>{{model.html}}</td>
		<td v-html="model.html"></td>
		<td>
			<i class="tables__edit-button fa fa-pencil" data-modal="#editIconTableItemModal" :data-id="model.id"></i>
			<i class="tables__edit-button fa fa-trash" @click="clickDelete"></i>
		</td>
	</tr>
</script>

<script type="text/x-template" id="icon-listing-item-edit-font-modal-template">
	<form data-url="/iconTableItem" data-action="editFont" method="post" role="form" enctype="multipart/form-data">
		<div class="modal-body">
			<input :value="itemID" type="text" name="item_id" style="display: none;">
			<div class="form-group">
				<label class="sr-only" for="font_id">The font</label>
				<select v-model="selectedFontID" v-show="fonts.length > 0" name="font_id" id="font_id">
					<option v-for="font in fonts" :value="font.id" :disabled="font.id == originalFontID">{{font.name}}</option>
				</select>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<input v-show="originalFontID != selectedFontID" type="submit" name="submit" class="btn btn-primary" value="Save"/>
		</div>
	</form>
</script>

<script type="text/x-template" id="segmented-element-item-template">
	<section class="editable-section">
		<div class="sg-config-detail__header">
			<span class="sg-config-detail__header-buttons__sub editable-section__button-container">
				<a v-show="data.item.images.length > 1" tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-arrows" data-modal="#arrangeElementItemModal" :data-id="data.id"></a>
				<a v-show="data.item.images.length < 6" tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-plus" data-modal="#addElementItemModal" :data-id="data.id"></a>
			</span>
			<h4 class="sg-config-detail__desc type__title type__title--darker">Images</h4>
		</div>
		
		<div class="sg-config-detail__table-wrapper">
			<?php
				$tableModel = new TableModel(null, true, true, '<tr is="segmented-element-item-row" v-for="(item, index) in data.item.images" :model="item" :index="index" :item-id="data.id" v-on:delete="deleteUpload"></tr>');
				
				$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('File Name'));
				$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Path'));
				$tableModel->addTableHeaderColumn(new TableHeaderColumnModel(null, null, false, false, true));
				
				$view = new TableView($tableModel, $currentUser);
				echo $view->output();
			?>
		</div>
	</section>
</script>

<script type="text/x-template" id="segmented-element-item-row-template">
	<tr :class="{odd: index % 2 == 0, even: index % 2 != 0}">
		<td>{{model.fileName}}</td>
		<td>{{model.path}}</td>
		<td>
			<i class="tables__edit-button fa fa-trash" @click="clickDelete"></i>
		</td>
	</tr>
</script>

<script type="text/x-template" id="segmented-element-item-add-modal-template">
	<form data-url="/elementItem" data-action="addUpload" method="post" role="form">
		<div class="modal-body">
			<ul class="sg-config-uploads sg-config-uploads__list">
				<segmented-element-item-add-modal-item :model="uploads" :selected-item="selectedItem" :original-upload-ids="originalUploadIDs" v-on:select="selectItem"></segmented-element-item-add-modal-item>
			</ul>
			<input :value="itemID" type="text" name="item_id" style="display: none;">
			<input v-model="selectedUploadID" type="text" name="upload_id" style="display: none;">
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<input v-show="selectedUploadID" type="submit" name="submit" class="btn btn-primary" value="Save"/>
		</div>
	</form>
</script>

<script type="text/x-template" id="segmented-element-item-add-modal-item-template">
	<li>
		<a class="sg-config-uploads__item" :class="{'sg-config-uploads__folder': isFolder, 'sg-config-uploads__file': !isFolder, 'sg-config-uploads__item--active': isSelected}">
			<span v-if="isFolder" class="fa sg-config-uploads__folder__icon" :class="{'sg-config-uploads__folder__icon--open': open, 'sg-config-uploads__folder__icon--closed': !open}" @click="toggle"></span>
			<span @click="select">{{model.name}}</span>
		</a>
		<ul v-if="isFolder" v-show="open" class="sg-config-uploads__list">
			<segmented-element-item-add-modal-item v-for="model in model.children" :model="model" :selected-item="selectedItem" :original-upload-ids="originalUploadIds" v-on:select="selectItem"></segmented-element-item-add-modal-item>
		</ul>
	</li>
</script>

<script type="text/x-template" id="columns-template">
	<section class="editable-section">
		<div class="sg-config-detail__header">
			<span class="sg-config-detail__header-buttons__sub editable-section__button-container">
				<a tabindex="0" role="button" class="sg-config-detail__header-button editable-section__button fa fa-pencil" data-modal="#editItemColumnsModal" :data-id="itemId"></a>
			</span>
			<h4 class="sg-config-detail__desc type__title type__title--darker">Columns</h4>
		</div>
				
		<div class="sg-config-detail__table-wrapper">
			<?php
				$tableModel = new TableModel(null, true, false, '<tr is="columns-row" :model="model" :index="0"></tr>');
				
				$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Lg'));
				$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Md'));
				$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Sm'));
				$tableModel->addTableHeaderColumn(new TableHeaderColumnModel('Xs'));
				
				$view = new TableView($tableModel, $currentUser);
				echo $view->output();
			?>
		</div>
	</section>
</script>

<script type="text/x-template" id="columns-row-template">
	<tr :class="{odd: index % 2 == 0, even: index % 2 != 0}">
		<td>{{model.lg}}</td>
		<td>{{model.md}}</td>
		<td>{{model.sm}}</td>
		<td>{{model.xs}}</td>
	</tr>
</script>

<script type="text/x-template" id="columns-modal-template">
	<form data-url="/item" data-action="editColumns" method="post" role="form">
		<div class="modal-body">
			<input :value="itemID" type="text" name="item_id" style="display: none;">
			<div class="form-group">
				<label for="lg">Large Screens</label>
				<input v-model="columns.lg" type="number" class="form-control" name="lg" id="lg" placeholder="LG" :min="mins.lg" max="12" v-on:input="lgChanged()" required/>
			</div>
			<div class="form-group">
				<label for="md">Medium Screens</label>
				<input v-model="columns.md" type="number" class="form-control" name="md" id="md" placeholder="MD" :min="Math.max(mins.md, columns.lg)" max="12" v-on:input="mdChanged()" required/>
			</div>
			<div class="form-group">
				<label for="sm">Small Screens</label>
				<input v-model="columns.sm" type="number" class="form-control" name="sm" id="sm" placeholder="SM" :min="Math.max(mins.sm, columns.md)" max="12" v-on:input="smChanged()" required/>
			</div>
			<div class="form-group">
				<label for="xs">Extra Small Screens</label>
				<input v-model="columns.xs" type="number" class="form-control" name="xs" id="xs" placeholder="XS" :min="Math.max(mins.xs, columns.sm)" max="12" required/>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<input type="submit" name="submit" class="btn btn-primary" value="Save"/>
		</div>
	</form>
</script>