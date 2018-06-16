<?php

class Upload extends DBItemParent
{
	public $filePath;
	public $parentID;
	
	protected static $tableName = 'sg_upload';
	
	public function __construct(Db $db, int $id = null, $code = null, $subordinateTableName = null)
	{
		if (!$id && (!$code && $subordinateTableName))
		{
			return;
		}
		
		parent::__construct($db, $id, $this->typeIDFromCode($db, $code), self::$tableName, $subordinateTableName);
	}
	
	private function typeIDFromCode(Db $db, string $code = null)
	{
		if ($code)
		{
			$query = 'select id from sg_upload_type where code = ?';
			$rows = $db->select($query, 's', array(&$code));
			
			if (count($rows) > 0)
			{
				return $rows[0]['id'];
			}
		}
	}
	
	public function write()
	{
		$this->writeTypeID(self::$tableName);
		
		$this->writeBase($this->filePath, 'filePath', self::$tableName, true);
		$this->writeBase($this->parentID, 'parentID', self::$tableName, false, true);
	}
	
	public function read(string $subordinateTableName = null)
	{
		$this->readWhole(self::$tableName, $subordinateTableName);
	}
	
	public function readExtra()
	{
		$this->readType('UploadType');
	}
	
	public function delete()
	{
		parent::deleteBase(self::$tableName);
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