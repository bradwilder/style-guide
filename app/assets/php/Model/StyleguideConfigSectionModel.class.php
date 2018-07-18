<?php

class StyleguideConfigSectionModel extends Model_base
{
	public $id;
	public $name;
	public $enabled;
	
	public function delete()
	{
		if ($this->id)
		{
			$section = new StyleguideSection($this->db, $this->id);
			$section->delete();
		}
		else
		{
			throw new Exception('Section ID must be set');
		}
	}
	
	public function nameExists()
	{
		if ($this->name)
		{
			return StyleguideSection::nameExists($this->db, $this->name, $this->id);
		}
		else
		{
			throw new Exception('Section name must be set');
		}
	}
	
	public function getSection()
	{
		if ($this->id)
		{
			$section = new StyleguideSection($this->db, $this->id);
			$section->read();
			return $section;
		}
		else
		{
			throw new Exception('Section ID must be set');
		}
	}
	
	public function editSection()
	{
		if ($this->id)
		{
			$section = new StyleguideSection($this->db, $this->id);
			$section->name = $this->name;
			$section->write();
		}
		else
		{
			throw new Exception('Section id must be set');
		}
	}
	
	public function enableSection()
	{
		if ($this->id &&isset($this->enabled))
		{
			$section = new StyleguideSection($this->db, $this->id);
			$section->enabled = $this->enabled;
			$section->write();
		}
		else
		{
			throw new Exception('Section id and enabled value must be set');
		}
	}
	
	public function addSection()
	{
		if ($this->name)
		{
			$section = new StyleguideSection($this->db);
			$section->name = $this->name;
			$section->enabled = $this->enabled;
			$section->userCreated = true;
			$section->write();
		}
		else
		{
			throw new Exception('Section name must be set');
		}
	}
}

?>