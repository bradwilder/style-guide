<?php

class StyleguideConfigColorsModel extends Model_base
{
	public $id;
	public $name;
	public $hex;
	public $variant1;
	public $variant2;
	
	public function setDefaultColor()
	{
		$this->db->query('delete from sg_color_default');
		
		if ($this->id)
		{
			$this->db->query('insert into sg_color_default (color_id) values (?)', 'i', [&$this->id]);
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
	
	public function delete()
	{
		if ($this->id)
		{
			$color = new Color($this->db, $this->id);
			$color->delete();
		}
		else
		{
			throw new Exception('Color ID must be set');
		}
	}
	
	public function nameExists()
	{
		if ($this->name)
		{
			return Color::nameExists($this->db, $this->name, $this->id);
		}
		else
		{
			throw new Exception('Color name must be set');
		}
	}
	
	public function getColor()
	{
		if ($this->id)
		{
			$color = new Color($this->db, $this->id);
			$color->read();
			return $color;
		}
		else
		{
			throw new Exception('Color ID must be set');
		}
	}
	
	public function editColor()
	{
		if ($this->id)
		{
			$color = new Color($this->db, $this->id);
			$color->read(); // Read so that the current variant1/variant2 will be set
			
			$color->name = $this->name;
			$color->hex = $this->hex;
			if (isset($this->variant1))
			{
				$color->variant1 = $this->variant1;
			}
			if (isset($this->variant2))
			{
				$color->variant2 = $this->variant2;
			}
			$color->write();
		}
		else
		{
			throw new Exception('Color id must be set');
		}
	}
	
	public function addColor()
	{
		if ($this->name && $this->hex)
		{
			$color = new Color($this->db);
			$color->name = $this->name;
			$color->hex = $this->hex;
			$color->variant1 = $this->variant1;
			$color->variant2 = $this->variant2;
			$color->write();
		}
		else
		{
			throw new Exception('Color name and hex must be set');
		}
	}
}

?>