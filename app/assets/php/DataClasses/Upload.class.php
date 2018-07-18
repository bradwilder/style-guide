<?php

class Upload extends DBItemParent
{
	public $filePath;
	public $parentID;
	
	protected static $tableName = 'sg_upload';
	private static $typeTableName = 'sg_upload_type';
	private static $typeClassName = 'UploadType';
	
	public function __construct(Db $db, int $id = null, string $code = null, string $subordinateTableName = null)
	{
		if (!$id && (!$code && $subordinateTableName))
		{
			return;
		}
		
		parent::__construct($db, self::$tableName, $id, $code, self::$typeTableName, self::$typeClassName, $subordinateTableName);
		
		$this->addColumn('filePath', new DBColumn(DBColumnType::String));
		$this->addColumn('parentID', new DBColumn(DBColumnType::Numeric, true));
	}
	
	public function readSubExtra() {}
	
	public function isFolder()
	{
		if (!$this->type)
		{
			$this->read();
			$this->readExtra();
		}
		
		return $this->type->code == 'folder';
	}
	
	public static function uploadExists(Db $db, string $name, int $folderID)
	{
		$query = 'select count(*) as count from ' . self::$tableName . ' where filePath = ? and parentID <=> ?';
		$row = $db->select($query, 'si', [&$name, &$folderID])[0];
		return $row['count'] > 0;
	}
	
	public static function getUploadPath(Db $db, int $parentPathID)
	{
		$path = '';
		while ($parentPathID)
		{
			$query = 'select filePath, parentID from ' . self::$tableName . ' where id = ?';
			$row = $db->select($query, 'i', [&$parentPathID])[0];
			
			$pathElementName = $row['filePath'];
			$parentPathID = $row['parentID'];
			
			$path = $pathElementName . '/' . $path;
		}
		
		return $path;
	}
}

?>