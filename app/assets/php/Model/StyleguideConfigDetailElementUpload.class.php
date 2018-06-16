<?php

class StyleguideConfigDetailElementUpload
{
	public $id;
	public $fileName;
	public $path;

	public function __construct($id, $fileName, $path)
	{
		$this->id = $id;
		$this->fileName = $fileName;
		$this->path = $path;
	}
}

?>