<?php

class StyleguideItemTypeColumnMinFactory
{
	public static function readColMinByTypeID($typeID, $code = null)
	{
		$db = new Db();
		
		$query = 'select id from sg_item_type_column_min where typeID = ?';
		$types = 'i';
		$params = [&$typeID];
		if ($code)
		{
			$query .= ' and code = ?';
			$types .= 'i';
			$params []= $code;
		}
		$id = $db->select($query, $types, $params)[0]['id'];
		
		$min = new StyleguideItemTypeColumnMin($db, $id);
		$min->read();
		return $min;
	}
}

?>