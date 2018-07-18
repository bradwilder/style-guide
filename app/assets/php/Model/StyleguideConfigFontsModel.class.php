<?php

class StyleguideConfigFontsModel extends Model_base
{
	public $id;
	public $name;
	public $alphabetID;
	public $typeCode;
	public $uploadFile;
	public $cssFile;
	public $importURL;
	public $website;
	
	public function delete()
	{
		if ($this->id)
		{
			$font = FontFactory::readFontByID($this->id);
			
			if (is_a($font, 'CSSFont'))
			{
				if ($font->directory)
				{
					rrmdir(__SITE_PATH . '/uploads/style-guide/' . $font->directory);
				}
				else
				{
					unlink(__SITE_PATH . '/uploads/style-guide/' . $font->cssFile);
				}
			}
			
			$font->delete();
		}
		else
		{
			throw new Exception('Font ID must be set');
		}
	}
	
	public function nameExists()
	{
		if ($this->name)
		{
			return Font::nameExists($this->db, $this->name, $this->id);
		}
		else
		{
			throw new Exception('Font name must be set');
		}
	}
	
	public function getFont()
	{
		if ($this->id)
		{
			$font = FontFactory::readFontByID($this->id);
			$font->readExtra();
			return $font;
		}
		else
		{
			throw new Exception('Font ID must be set');
		}
	}
	
	public function editFont()
	{
		if ($this->id)
		{
			$font = FontFactory::readFontByID($this->id);
			
			$font->name = $this->name;
			$font->alphabetID = $this->alphabetID;
			
			if (is_a($font, 'WebFont'))
			{
				$font->importURL = $this->importURL;
				$font->website = $this->website;
			}
			elseif (is_a($font, 'CSSFont'))
			{
				if ($this->uploadFile && strlen($this->uploadFile['name']) > 0)
				{
					$file = $this->uploadFile['tmp_name'];
					$name = basename($this->uploadFile['name']);
					
					if ($font->directory)
					{
						rrmdir(__SITE_PATH . '/uploads/style-guide/' . $font->directory);
					}
					else
					{
						unlink(__SITE_PATH . '/uploads/style-guide/' . $font->cssFile);
					}
					
					$mimeType = mime_content_type($file);
					$uploaddir = __SITE_PATH . '/uploads/style-guide/';
					$uploadfile = $uploaddir . $name;
					
					if (move_uploaded_file($file, $uploadfile))
					{
						if ($mimeType == 'text/css' || $mimeType == 'text/plain')
						{
							$font->cssFile = $name;
							$font->directory = null;
						}
						else
						{
							$zip = new ZipArchive;
							$res = $zip->open($uploadfile);
							if ($res === TRUE)
							{
								$zip->extractTo($uploaddir);
								$zip->close();
								
								unlink($uploadfile);
								
								$folder = basename($name, '.zip');
								
								$font->cssFile = $this->cssFile;
								$font->directory = $folder;
							}
							else
							{
								echo 'File unzip failed';
								return;
							}
						}
					}
					else
					{
						echo "File upload failed";
						return;
					}
				}
			}
			
			$font->write();
		}
		else
		{
			throw new Exception('Font id must be set');
		}
	}
	
	public function addFont()
	{
		if ($this->typeCode && $this->name)
		{
			$font = FontFactory::createByCode($this->typeCode);
			$font->name = $this->name;
			$font->alphabetID = $this->alphabetID;
			
			if ($this->typeCode == 'css')
			{
				$file = $this->uploadFile['tmp_name'];
				$name = basename($this->uploadFile['name']);
				$mimeType = mime_content_type($file);
				$uploaddir = __SITE_PATH . '/uploads/style-guide/';
				$uploadfile = $uploaddir . $name;
				
				if (move_uploaded_file($file, $uploadfile))
				{
					if ($mimeType == 'text/css' || $mimeType == 'text/plain')
					{
						$font->cssFile = $name;
					}
					else
					{
						$zip = new ZipArchive;
						$res = $zip->open($uploadfile);
						if ($res === TRUE)
						{
							$zip->extractTo($uploaddir);
							$zip->close();
							
							unlink($uploadfile);
							
							$folder = basename($name, '.zip');
							
							$font->directory = $folder;
							$font->cssFile = $this->cssFile;
						}
						else
						{
							echo 'File unzip failed';
							return;
						}
					}
				}
				else
				{
					echo "File upload failed";
					return;
				}
			}
			else if ($this->typeCode == 'web')
			{
				$font->importURL = $this->importURL;
				$font->website = $this->website;
			}
			
			$font->write();
		}
		else
		{
			throw new Exception('Font code and name must be set');
		}
	}
	
	public function getAlphabets()
	{
		return $this->db->select('select id, name from sg_font_alphabet order by name');
	}
	
	public function getFontTypes()
	{
		return $this->db->select('select code, description from sg_font_type order by description');
	}
}

?>