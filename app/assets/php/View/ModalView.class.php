<?php

class ModalView extends View_base
{
	public function __construct(ModalModel $model, $currentUser)
	{
		parent::__construct($model, $currentUser, new Template(__ASSETS_PATH . '/php/View/Template/Page-modal.template.php'));
	}
	
	public function output()
	{
		$this->template->id = $this->model->id;
		$this->template->title = $this->model->title;
		$this->template->content = $this->getContent();
		
		return parent::output();
	}
	
	
	private function getContent()
	{
		ob_start();
		require(__ASSETS_PATH . '/php/View/Template/' . $this->model->template);
		return ob_get_clean();
	}
}

?>