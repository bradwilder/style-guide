<?php

class StyleguideConfigColorsModel extends Model_base
{
	public function setDefaultColor(int $id)
	{
		$this->db->query('delete from sg_color_default');
		
		if ($id)
		{
			$this->db->query('insert into sg_color_default (color_id) values (?)', 'i', [&$id]);
		}
	}
	
	public function getDefaultColor()
	{
		$rows = $this->db->select('select c.hex from sg_color_default cd join sg_color c on c.id = cd.color_id');
		if (count($rows) > 0)
		{
			return $rows[0]['hex'];
		}
		
		return null;
	}
	
	public function delete(int $id)
	{
		$color = new Color($this->db, $id);
		$color->delete();
	}
	
	public function nameExists(string $name, int $id)
	{
		return Color::nameExists($this->db, $name, $id);
	}
	
	public function getColor(int $id)
	{
		$color = new Color($this->db, $id);
		$color->read();
		return $color;
	}
	
	public function editColor(int $id, string $name, string $hex, string $variant1, string $variant2)
	{
		$color = new Color($this->db, $id);
		$color->read(); // Read so that the current variant1/variant2 will be set
		
		$color->name = $name;
		$color->hex = $hex;
		if (isset($variant1))
		{
			$color->variant1 = $variant1;
		}
		if (isset($variant2))
		{
			$color->variant2 = $variant2;
		}
		$color->write();
	}
	
	public function addColor(string $name, string $hex, string $variant1, string $variant2)
	{
		$color = new Color($this->db);
		$color->name = $name;
		$color->hex = $hex;
		$color->variant1 = $variant1;
		$color->variant2 = $variant2;
		$color->write();
	}
}

?>