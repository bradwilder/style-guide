<?php

class StyleguideIconTableItemView extends StyleguideItemView
{
	public function __construct(StyleguideIconTableItemModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/Icons.template.php'));
	}
}

?>