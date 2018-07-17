<?php

abstract class StyleguideItemModel extends Model_base
{
	public $foreignItem;
	
	public abstract function getData();
	
	public function getColumnData()
	{
		if ($this->foreignItem)
		{
			$row = $this->db->select('select i.colLg, i.colMd, i.colSm, i.colXs from sg_item i join sg_item_type it on it.id = i.typeID where i.id = ? and it.code = ?', 'is', [&$this->foreignItem->itemID, &$this->foreignItem->code])[0];
			return new StyleguideItemColumns($row['colXs'], $row['colSm'], $row['colMd'], $row['colLg']);
		}
		else
		{
			throw new Exception('ID and code must be set');
		}
	}
}

?>