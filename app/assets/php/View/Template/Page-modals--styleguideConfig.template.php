<?php

if (!function_exists('echoModal'))
{
	function echoModal($id, $title, $template)
	{
		$modalModel = new ModalModel();
		
		$modalModel->id = $id;
		$modalModel->title = $title;
		$modalModel->template = $template;

		$modalView = new ModalView($modalModel, $currentUser);
		echo $modalView->output();
	}
}


echoModal('editColorModal', 'Edit Color', 'Modal--editColor.template.php');
echoModal('addColorModal', 'Add Color', 'Modal--addColor.template.php');
echoModal('viewFontModal', 'View Font', 'Modal--viewFont.template.php');
echoModal('editFontModal', 'Edit Font', 'Modal--editFont.template.php');
echoModal('addFontModal', 'Add Font', 'Modal--addFont.template.php');
echoModal('viewFileModal', 'View Upload', 'Modal--viewFile.template.php');
echoModal('editFileModal', 'Edit Upload', 'Modal--editFile.template.php');
echoModal('addFileModal', 'New Upload', 'Modal--addFile.template.php');
echoModal('editFolderModal', 'Edit Folder', 'Modal--editFolder.template.php');
echoModal('addFolderModal', 'Add Folder', 'Modal--addFolder.template.php');
echoModal('arrangeSectionsModal', 'Arrange Sections', 'Modal--arrange.template.php');
echoModal('addSectionModal', 'Add Section', 'Modal--addStyleguideSection.template.php');
echoModal('editSectionModal', 'Edit Section', 'Modal--editStyleguideSection.template.php');
echoModal('arrangeSubsectionsModal', 'Arrange Subsections', 'Modal--arrange.template.php');
echoModal('addSubsectionModal', 'Add Subsection', 'Modal--addStyleguideSubsection.template.php');
echoModal('editSubsectionModal', 'Edit Subsection', 'Modal--editStyleguideSubsection.template.php');
echoModal('arrangeSubSubsectionsModal', 'Arrange Sub-Subsections', 'Modal--arrange.template.php');
echoModal('arrangeItemsModal', 'Arrange Items', 'Modal--arrange.template.php');
echoModal('editItemModal', 'Edit Item', 'Modal--editItem.template.php');
echoModal('addItemModal', 'Add Item', 'Modal--addItem.template.php');
echoModal('editItemColumnsModal', 'Edit Columns', 'Modal--editItemColumns.template.php');
echoModal('editColorItemModal', 'Pick Color', 'Modal--editColorItem.template.php');
echoModal('addColorItemModal', 'Add Color', 'Modal--addColorItem.template.php');
echoModal('arrangeColorItemModal', 'Arrange Colors', 'Modal--arrange-id.template.php');
echoModal('editColorItemDescriptorModal', 'Edit Descriptor', 'Modal--editColorItemDescriptor.template.php');
echoModal('addColorItemDescriptorModal', 'Add Descriptor', 'Modal--addColorItemDescriptor.template.php');
echoModal('arrangeColorItemDescriptorsModal', 'Arrange Descriptors', 'Modal--arrange.template.php');
echoModal('editFontFamilyItemModal', 'Pick Font', 'Modal--editFontFamily.template.php');
echoModal('arrangeFontTableItemListingsModal', 'Arrange Listings', 'Modal--arrange.template.php');
echoModal('addFontTableItemListingModal', 'Add Listing', 'Modal--addFontTableListing.template.php');
echoModal('editFontTableItemListingModal', 'Edit Listing', 'Modal--editFontTableListing.template.php');
echoModal('editFontTableItemFontModal', 'Pick Font', 'Modal--editFontTableFont.template.php');
echoModal('addFontTableItemListingCSSModal', 'Add Listing CSS', 'Modal--addFontTableListingCSS.template.php');
echoModal('editFontTableItemListingCSSModal', 'Edit Listing CSS', 'Modal--editFontTableListingCSS.template.php');
echoModal('editIconTableItemFontModal', 'Pick Font', 'Modal--editIconTableFont.template.php');
echoModal('addIconTableItemModal', 'Add HTML', 'Modal--addIconTableItem.template.php');
echoModal('editIconTableItemModal', 'Edit HTML', 'Modal--editIconTableItem.template.php');
echoModal('arrangeIconTableItemModal', 'Arrange Icons', 'Modal--arrange.template.php');
echoModal('addElementItemModal', 'Add Image', 'Modal--addElementItem.template.php');
echoModal('arrangeElementItemModal', 'Arrange Images', 'Modal--arrange-id.template.php');

?>