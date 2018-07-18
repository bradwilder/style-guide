<?php

class StyleguideConfigSectionModel extends Model_base
{
	public function delete(int $id)
	{
		$section = new StyleguideSection($this->db, $id);
		$section->delete();
	}
	
	public function nameExists(string $name, int $id)
	{
		return StyleguideSection::nameExists($this->db, $name, $id);
	}
	
	public function getSection(int $id)
	{
		$section = new StyleguideSection($this->db, $id);
		$section->read();
		return $section;
	}
	
	public function editSection(int $id, string $name)
	{
		$section = new StyleguideSection($this->db, $id);
		$section->name = $name;
		$section->write();
	}
	
	public function enableSection(int $id, bool $enabled)
	{
		$section = new StyleguideSection($this->db, $id);
		$section->enabled = $enabled;
		$section->write();
	}
	
	public function addSection(string $name, bool $enabled)
	{
		$section = new StyleguideSection($this->db);
		$section->name = $name;
		$section->enabled = $enabled;
		$section->userCreated = true;
		$section->write();
	}
}

?>