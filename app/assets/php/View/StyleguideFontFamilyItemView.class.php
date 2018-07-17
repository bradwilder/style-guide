<?php

class StyleguideFontFamilyItemView extends StyleguideItemView
{
	public function __construct(StyleguideFontFamilyItemModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/FontFamily.template.php'));
	}
}

?>