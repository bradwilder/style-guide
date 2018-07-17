<?php

class StyleguideColorDescriptorItemView extends StyleguideItemView
{
	public function __construct(StyleguideColorDescriptorItemModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/ColorSwatch.template.php'));
	}
}

?>