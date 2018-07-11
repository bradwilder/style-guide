<?php

class Upload extends DBItemParent
{
	public $filePath;
	public $parentID;
	
	protected static $tableName = 'sg_upload';
	private static $typeTableName = 'sg_upload_type';
	
	public function __construct(Db $db, int $id = null, string $code = null, string $subordinateTableName = null)
	{
		if (!$id && (!$code && $subordinateTableName))
		{
			return;
		}
		
		parent::__construct($db, self::$tableName, $id, $code, self::$typeTableName, $subordinateTableName);
	}
	
	public function write()
	{
		parent::writeBaseParent();
		
		$this->writeBase($this->filePath, 'filePath', true);
		$this->writeBase($this->parentID, 'parentID', false, true);
	}
	
	public function read(string $subordinateTableName = null)
	{
		$this->readWhole($subordinateTableName);
	}
	
	public function readExtra()
	{
		$this->readType('UploadType');
	}
	
	public function delete()
	{
		parent::deleteBase();
	}
	
	public function isFolder()
	{
		if (!$this->type)
		{
			$this->read();
			$this->readExtra();
		}
		
		return $this->type->code == 'folder';
	}
	
	public static function uploadExists(string $name, int $folderID)
	{
		$db = new Db();
		
		$query = 'select count(*) as count from ' . self::$tableName . ' where filePath = ? and parentID <=> ?';
		$row = $db->select($query, 'si', array(&$name, &$folderID))[0];
		return $row['count'] > 0;
	}
	
	public static function getUploadPath(int $parentPathID)
	{
		$db = new Db();
		
		$path = '';
		while ($parentPathID)
		{
			$query = 'select filePath, parentID from ' . self::$tableName . ' where id = ?';
			$row = $db->select($query, 'i', array(&$parentPathID))[0];
			
			$pathElementName = $row['filePath'];
			$parentPathID = $row['parentID'];
			
			$path = $pathElementName . '/' . $path;
		}
		
		return $path;
	}
}

?>