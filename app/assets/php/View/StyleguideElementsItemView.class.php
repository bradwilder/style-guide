<?php

class StyleguideElementsItemView extends StyleguideItemView
{
	public function __construct(StyleguideElementsItemModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/ElementListing.template.php'));
	}
}

?>