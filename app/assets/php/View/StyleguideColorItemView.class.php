<?php

class StyleguideColorItemView extends StyleguideItemView
{
	public function __construct(StyleguideColorItemModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/ColorTile.template.php'));
	}
}

?>