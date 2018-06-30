<?php

if (!function_exists('echoModal'))
{
	function echoModal($id, $title, $template)
	{
		$modalModel = new ModalModel();
		
		$modalModel->id = $id;
		$modalModel->title = $title;
		$modalModel->template = $template;

		$modalView = new ModalView($modalModel);
		echo $modalView->output();
	}
}


echoModal('uploadImageModal', 'Upload Image', 'Modal--uploadImage.template.php');
echoModal('manageImagesModal', 'Manage Images', 'Modal--manageImages.template.php');
echoModal('newSectionModal', 'New Section', 'Modal--addMoodboardSection.template.php');
echoModal('arrangeSectionsModal', 'Arrange Sections', 'Modal--arrange.template.php');
echoModal('addImagesModal', 'Add Images', 'Modal--addImages.template.php');
echoModal('arrangeMoodboardSectionImagesModal', 'Arrange Images', 'Modal--arrange-id.template.php');

?>

<ul id="editSectionPopover" style="display: none;">
	<li><a href="#" class="type__title--darker edit-section__trigger">Edit Section</a></li>
	<li><a href="#" class="type__title--darker" data-modal="#addImagesModal">Add Images</a></li>
	<li><a href="#" class="type__title--darker" data-modal="#arrangeMoodboardSectionImagesModal">Arrange Images</a></li>
	<li><a href="#" class="type__title--darker delete-section">Delete Section</a></li>
</ul>

<ul id="editImagePopover" style="display: none;">
	<li><a href="#" class="type__title--darker remove-image">Remove Image</a></li>
</ul>

<form id="commentReplyForm" class="editable-section__form" method="post" role="form">
	<textarea class="comment__body form-control type__desc editable-section__input" name="reply" cols="45" rows="8" placeholder="Comment" required></textarea>
	<button type="button" class="comment__reply-submit editable-section__input btn btn-primary">Reply</button>
</form>

<div class="editable-section__overlay editable-section__overlay--opaque"></div>
<div class="editable-section__overlay editable-section__overlay--clear"></div>