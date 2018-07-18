<?php

abstract class StyleguideItemModel extends Model_base
{
	public $foreignID;
	
	public abstract function getData();
	public abstract function getConfigData();
	
	public function getColumnData()
	{
		if ($this->foreignID)
		{
			$row = $this->db->select('select colLg, colMd, colSm, colXs from sg_item where id = ?', 'i', [&$this->foreignID])[0];
			return new StyleguideItemColumns($row['colXs'], $row['colSm'], $row['colMd'], $row['colLg']);
		}
		else
		{
			throw new Exception('ID must be set');
		}
	}
}

?>