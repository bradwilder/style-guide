<?php

class StyleguideConfigUploadsController extends Controller_base
{
	public function __construct(StyleguideConfigUploadsModel $model)
	{
		parent::__construct($model);
	}
	
	public function uploadFile()
	{
		$ret = $this->model->uploadFile($_POST['folder_id'], $_POST['name'], $_FILES['upload'], $_POST['shortName'], $_POST['longName']);
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function newFolder()
	{
		$ret = $this->model->newFolder($_POST['folder_id'], $_POST['name']);
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function delete()
	{
		$ret = $this->model->delete($_POST['upload_id']);
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function get()
	{
		echo json_encode($this->model->getUpload($_GET['upload_id']));
	}
	
	public function editFile()
	{
		$ret = $this->model->editFile($_POST['upload_id'], $_POST['name'], $_FILES['upload'], $_POST['shortName'], $_POST['longName']);
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function editFolder()
	{
		$ret = $this->model->editFolder($_POST['folder_id'], $_POST['name']);
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
}

?>