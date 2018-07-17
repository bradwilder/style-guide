<?php

class UploadFolder extends Upload
{
	private static $code = 'folder';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$code);
	}
	
	public function getChildren()
	{
		$children = [];
		$query = 'select u.id, case when t.code = "folder" then 1 else 0 end as isFolder from ' . self::$tableName . ' u join sg_upload_type t on t.id = u.typeID where u.parentID = ?';
		$rows = $this->db->select($query, 'i', [&$this->id]);
		
		foreach ($rows as $row)
		{
			$childUploadID = $row['id'];
			$isFolder = ($row['isFolder'] == 1);
			
			$upload;
			if ($isFolder)
			{
				$upload = new UploadFolder($this->db, $childUploadID);
			}
			else
			{
				$upload = new UploadFile($this->db, $childUploadID);
			}
			
			$children []= $upload;
		}
		
		return $children;
	}
}

?>