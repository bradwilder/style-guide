<?php

class UploadFactory
{
	public static function readUploadByID($uploadID)
	{
		$db = new Db();
		
		$query = 'select case when t.code = "folder" then 1 else 0 end as isFolder from sg_upload u join sg_upload_type t on t.id = u.typeID where u.id = ?';
		$isFolder = ($db->select($query, 'i', [&$uploadID])[0]['isFolder'] == 1);
		
		$upload;
		if ($isFolder)
		{
			$upload = new UploadFolder($db, $uploadID);
		}
		else
		{
			$upload = new UploadFile($db, $uploadID);
		}
		$upload->read();
		return $upload;
	}
	
	public static function create($isFolder)
	{
		$db = new Db();
		if ($isFolder)
		{
			return new UploadFolder($db);
		}
		else
		{
			return new UploadFile($db);
		}
	}
}

?>