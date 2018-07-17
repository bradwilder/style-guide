<?php

class StyleguideFontTableItemView extends StyleguideItemView
{
	public function __construct(StyleguideFontTableItemModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/FontListingTable.template.php'));
	}
}

?>