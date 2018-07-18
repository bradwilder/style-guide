<?php

class StyleguideConfigUploadsModel extends Model_base
{
	public function uploadFile(int $id, string $fileName, $uploadFile, string $shortName, string $longName)
	{
		if (Upload::uploadExists($this->db, $fileName, $id))
		{
			return 'Name already exists in this folder';
		}
		
		$path = Upload::getUploadPath($this->db, $id);
		$uploaddir = __ASSETS_PATH . '/img/uploads/style-guide/' . $path;
		$upload = $uploaddir . $fileName;
		
		$file = $uploadFile['tmp_name'];
		if (move_uploaded_file($file, $upload))
		{
			$fileUpload = new UploadFile($this->db);
			$fileUpload->filePath = $fileName;
			$fileUpload->parentID = $id;
			$fileUpload->shortName = $shortName;
			$fileUpload->fullName = $longName;
			$fileUpload->write();
		}
		else
		{
			return "File upload failed";
		}
	}
	
	public function newFolder(int $id, string $fileName)
	{
		if (Upload::uploadExists($this->db, $fileName, $id))
		{
			return 'Name already exists in this folder';
		}
		
		$path = Upload::getUploadPath($this->db, $id);
		$newdir = __ASSETS_PATH . '/img/uploads/style-guide/' . $path . $fileName;
		
		if (mkdir($newdir))
		{
			$folder = new UploadFolder($this->db);
			$folder->filePath = $fileName;
			$folder->parentID = $id;
			$folder->write();
		}
		else
		{
			echo "Directory creation failed";
			return;
		}
	}
	
	public function delete(int $id)
	{
		$upload = UploadFactory::readUploadByID($id);
		
		$parentPath = Upload::getUploadPath($this->db, $upload->parentID);
		
		$path = __ASSETS_PATH . '/img/uploads/style-guide/' . $parentPath . $upload->filePath;
		
		if ($upload->isFolder())
		{
			rrmdir($path);
		}
		else
		{
			unlink($path);
		}
		
		$folders = [];
		$files = [];
		
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
	
	public function getUpload(int $id)
	{
		return UploadFactory::readUploadByID($id);
	}
	
	public function editFile(int $id, string $fileName, $uploadFile, string $shortName, string $longName)
	{
		$fileUpload = new UploadFile($this->db, $id);
		$fileUpload->read();
		
		$fileUpload->shortName = $shortName;
		$fileUpload->fullName = $longName;
		
		$parentPath = Upload::getUploadPath($this->db, $fileUpload->parentID);
		$uploaddir = __ASSETS_PATH . '/img/uploads/style-guide/' . $parentPath;
		
		$file = $uploadFile['tmp_name'];
		if ($file)
		{
			$newFileName = $fileUpload->filePath;
			if ($fileName)
			{
				$newFileName = $fileName;
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
		else if ($fileName)
		{
			rename($uploaddir . $fileUpload->filePath, $uploaddir . $fileName);
			$fileUpload->filePath = $fileName;
		}
		
		$fileUpload->write();
	}
	
	public function editFolder(int $id, string $fileName)
	{
		$folder = new UploadFolder($this->db, $id);
		$folder->read();
		
		$parentPath = Upload::getUploadPath($this->db, $folder->parentID);
		$uploaddir = __ASSETS_PATH . '/img/uploads/style-guide/' . $parentPath;
		
		if (rename($uploaddir . $folder->filePath, $uploaddir . $fileName))
		{
			$folder->filePath = $fileName;
			$folder->write();
		}
		else
		{
			return "Folder move failed";
		}
	}
}

?>