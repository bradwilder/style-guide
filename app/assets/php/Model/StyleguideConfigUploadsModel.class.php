<?php

class StyleguideConfigUploadsModel extends Model_base
{
	public $id;
	public $fileName;
	public $shortName;
	public $longName;
	public $uploadFile;
	
	public function uploadFile()
	{
		if ($this->id && $this->fileName && $this->uploadFile)
		{
			if (Upload::uploadExists($this->fileName, $this->id))
			{
				return 'Name already exists in this folder';
			}
			
			$path = Upload::getUploadPath($this->id);
			$uploaddir = __ASSETS_PATH . '/img/uploads/style-guide/' . $path;
			$uploadfile = $uploaddir . $this->fileName;
			
			$file = $this->uploadFile['tmp_name'];
			if (move_uploaded_file($file, $uploadfile))
			{
				$fileUpload = new UploadFile($this->db);
				$fileUpload->filePath = $this->fileName;
				$fileUpload->parentID = $this->id;
				$fileUpload->shortName = $this->shortName;
				$fileUpload->fullName = $this->longName;
				$fileUpload->write();
			}
			else
			{
				return "File upload failed";
			}
		}
		else
		{
			throw new Exception('Folder ID, filename, and file must be set');
		}
	}
	
	public function newFolder()
	{
		if ($this->fileName)
		{
			if (Upload::uploadExists($this->fileName, $this->id))
			{
				return 'Name already exists in this folder';
			}
			
			$path = Upload::getUploadPath($this->id);
			$newdir = __ASSETS_PATH . '/img/uploads/style-guide/' . $path . $this->fileName;
			
			if (mkdir($newdir))
			{
				$folder = new UploadFolder($this->db);
				$folder->filePath = $this->fileName;
				$folder->parentID = $this->id;
				$folder->write();
			}
			else
			{
				echo "Directory creation failed";
				return;
			}
		}
		else
		{
			throw new Exception('Name must be set');
		}
	}
	
	public function delete()
	{
		if ($this->id)
		{
			$upload = UploadFactory::readUploadByID($this->id);
			
			$parentPath = Upload::getUploadPath($upload->parentID);
			
			$path = __ASSETS_PATH . '/img/uploads/style-guide/' . $parentPath . $upload->filePath;
			
			if ($upload->isFolder())
			{
				rrmdir($path);
			}
			else
			{
				unlink($path);
			}
			
			$folders = array();
			$files = array();
			
			if ($upload->isFolder())
			{
				$folders []= $upload;
			}
			else
			{
				$files []= $upload;
			}
			
			while (count($folders) > 0)
			{
				$folder = array_pop($folders);
				
				$children = $folder->getChildren();
				foreach ($children as $childUpload)
				{
					if ($childUpload->isFolder())
					{
						$folders []= $childUpload;
					}
					else
					{
						$files []= $childUpload;
					}
				}
			}
			
			foreach ($files as $file)
			{
				$file->delete();
			}
			
			$upload->delete();
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
	
	public function getUpload()
	{
		if ($this->id)
		{
			return UploadFactory::readUploadByID($this->id);
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
	
	public function editFile()
	{
		if ($this->id)
		{
			$fileUpload = new UploadFile($this->db, $this->id);
			$fileUpload->read();
			
			$fileUpload->shortName = $this->shortName;
			$fileUpload->fullName = $this->longName;
			
			$parentPath = Upload::getUploadPath($fileUpload->parentID);
			$uploaddir = __ASSETS_PATH . '/img/uploads/style-guide/' . $parentPath;
			
			$file = $this->uploadFile['tmp_name'];
			if ($file)
			{
				$newFileName = $fileUpload->filePath;
				if ($this->fileName)
				{
					$newFileName = $this->fileName;
					unlink($uploaddir . $fileUpload->filePath);
				}
				
				if (move_uploaded_file($file, $uploaddir . $newFileName))
				{
					$fileUpload->filePath = $newFileName;
				}
				else
				{
					return "File upload failed";
				}
			}
			else if ($this->fileName)
			{
				rename($uploaddir . $fileUpload->filePath, $uploaddir . $this->fileName);
				$fileUpload->filePath = $this->fileName;
			}
			
			$fileUpload->write();
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
	
	public function editFolder()
	{
		if ($this->id && $this->fileName)
		{
			$folder = new UploadFolder($this->db, $this->id);
			$folder->read();
			
			$parentPath = Upload::getUploadPath($folder->parentID);
			$uploaddir = __ASSETS_PATH . '/img/uploads/style-guide/' . $parentPath;
			
			if (rename($uploaddir . $folder->filePath, $uploaddir . $this->fileName))
			{
				$folder->filePath = $this->fileName;
				$folder->write();
			}
			else
			{
				return "Folder move failed";
			}
		}
		else
		{
			throw new Exception('Folder ID and name must be set');
		}
	}
}

?>