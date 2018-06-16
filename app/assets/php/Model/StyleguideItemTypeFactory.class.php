<?php

class StyleguideItemTypeFactory
{
	public static function readTypeByCode($code)
	{
		$db = new Db();
		
		$query = 'select id from sg_item_type where code = ?';
		$id = $db->select($query, 's', array(&$code))[0]['id'];
		
		$type = new StyleguideItemType($db, $id);
		$type->read();
		return $type;
	}
}

?>