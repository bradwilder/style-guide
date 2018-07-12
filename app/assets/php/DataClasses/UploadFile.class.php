<?php

class UploadFile extends Upload
{
	public $shortName;
	public $fullName;
	
	protected static $tableName = 'sg_upload_file';
	private static $code = 'file';
	
	public function __construct(Db $db, int $id = null)
	{
		parent::__construct($db, $id, self::$code, self::$tableName);
	}
	
	public function write()
	{
		parent::write();
		
		$this->writeSub($this->shortName, 'shortName', DBColumnType::String);
		$this->writeSub($this->fullName, 'fullName', DBColumnType::String);
	}
	
	public function read()
	{
		parent::read(true);
	}
	
	public function delete()
	{
		$query = 'select baseID from sg_element where upload1ID = ? or upload2ID = ? or upload3ID = ? or upload4ID = ? or upload5ID = ? or upload6ID = ?';
		$rows = $this->db->select($query, 'iiiiii', array(&$this->id, &$this->id, &$this->id, &$this->id, &$this->id, &$this->id));
		
		foreach ($rows as $row)
		{
			$element = new StyleguideElementItem($this->db, $row['baseID']);
			$element->delete();
		}
		
		parent::delete();
	}
}

?>