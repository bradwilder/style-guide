<?php

class StyleguideConfigUploadsController extends Controller_base
{
	public function __construct(StyleguideConfigUploadsModel $model)
	{
		parent::__construct($model);
	}
	
	public function uploadFile()
	{
		$this->model->id = $_POST['folder_id'];
		$this->model->fileName = $_POST['name'];
		$this->model->shortName = $_POST['shortName'];
		$this->model->longName = $_POST['longName'];
		$this->model->uploadFile = $_FILES['upload'];
		
		$ret = $this->model->uploadFile();
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function newFolder()
	{
		$this->model->id = $_POST['folder_id'];
		$this->model->fileName = $_POST['name'];
		
		$ret = $this->model->newFolder();
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function delete()
	{
		$this->model->id = $_POST['upload_id'];
		
		$ret = $this->model->delete();
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function get()
	{
		$this->model->id = $_GET['upload_id'];
		
		echo json_encode($this->model->getUpload());
	}
	
	public function editFile()
	{
		$this->model->id = $_POST['upload_id'];
		$this->model->fileName = $_POST['name'];
		$this->model->shortName = $_POST['shortName'];
		$this->model->longName = $_POST['longName'];
		$this->model->uploadFile = $_FILES['upload'];
		
		$ret = $this->model->editFile();
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
	
	public function editFolder()
	{
		$this->model->id = $_POST['folder_id'];
		$this->model->fileName = $_POST['name'];
		
		$ret = $this->model->editFolder();
		if ($ret)
		{
			setReturnHeaders(500, 'Internal Server Error', ['error' => 'Error: ' . $ret]);
		}
	}
}

?>