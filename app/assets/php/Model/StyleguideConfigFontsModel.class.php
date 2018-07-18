<?php

class StyleguideConfigFontsModel extends Model_base
{
	public function delete(int $id)
	{
		$font = FontFactory::readFontByID($id);
		
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
	
	public function nameExists(string $name, int $id)
	{
		return Font::nameExists($this->db, $name, $id);
	}
	
	public function getFont(int $id)
	{
		$font = FontFactory::readFontByID($id);
		$font->readExtra();
		return $font;
	}
	
	public function editFont(int $id, string $name, int $alphabetID, $uploadFile, string $cssFile, string $importURL, string $website)
	{
		$font = FontFactory::readFontByID($id);
		
		$font->name = $name;
		$font->alphabetID = $alphabetID;
		
		if (is_a($font, 'WebFont'))
		{
			$font->importURL = $importURL;
			$font->website = $website;
		}
		elseif (is_a($font, 'CSSFont'))
		{
			if ($uploadFile && strlen($uploadFile['name']) > 0)
			{
				$file = $uploadFile['tmp_name'];
				$name = basename($uploadFile['name']);
				
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
				$upload = $uploaddir . $name;
				
				if (move_uploaded_file($file, $upload))
				{
					if ($mimeType == 'text/css' || $mimeType == 'text/plain')
					{
						$font->cssFile = $name;
						$font->directory = null;
					}
					else
					{
						$zip = new ZipArchive;
						$res = $zip->open($upload);
						if ($res === TRUE)
						{
							$zip->extractTo($uploaddir);
							$zip->close();
							
							unlink($upload);
							
							$folder = basename($name, '.zip');
							
							$font->cssFile = $cssFile;
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
	
	public function addFont(string $name, int $alphabetID, string $typeCode, $uploadFile, string $cssFile, string $importURL, string $website)
	{
		$font = FontFactory::createByCode($typeCode);
		$font->name = $name;
		$font->alphabetID = $alphabetID;
		
		if ($typeCode == 'css')
		{
			$file = $uploadFile['tmp_name'];
			$name = basename($uploadFile['name']);
			$mimeType = mime_content_type($file);
			$uploaddir = __SITE_PATH . '/uploads/style-guide/';
			$upload = $uploaddir . $name;
			
			if (move_uploaded_file($file, $upload))
			{
				if ($mimeType == 'text/css' || $mimeType == 'text/plain')
				{
					$font->cssFile = $name;
				}
				else
				{
					$zip = new ZipArchive;
					$res = $zip->open($upload);
					if ($res === TRUE)
					{
						$zip->extractTo($uploaddir);
						$zip->close();
						
						unlink($upload);
						
						$folder = basename($name, '.zip');
						
						$font->directory = $folder;
						$font->cssFile = $cssFile;
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
		else if ($typeCode == 'web')
		{
			$font->importURL = $importURL;
			$font->website = $website;
		}
		
		$font->write();
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