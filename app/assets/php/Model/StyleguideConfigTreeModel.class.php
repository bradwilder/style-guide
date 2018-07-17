<?php

class StyleguideConfigTreeModel extends Model_base
{
	private function getSections()
	{
		$sections = [];
		
		$rows = $this->db->select('select id from sg_section order by position');
		foreach ($rows as $row)
		{
			$sgSection = new StyleguideSection($this->db, $row['id']);
			$sgSection->read();
			$section = new StyleguideConfigTreeSectionItem();
			$section->id = $sgSection->id;
			$section->name = $sgSection->name;
			$section->enabled = $sgSection->enabled;
			
			$sections []= $section;
			
			$rows = $this->db->select('select id from sg_subsection where parentSubsectionID is null and sectionID = ? order by position', 'i', [&$section->id]);
			foreach ($rows as $row)
			{
				$sgSubsection = new StyleguideSubsection($this->db, $row['id']);
				$sgSubsection->read();
				$subsection = new StyleguideConfigTreeSectionItem();
				$subsection->id = $sgSubsection->id;
				$subsection->name = $sgSubsection->name;
				$subsection->enabled = $sgSubsection->enabled;
				
				$section->subsections []= $subsection;
				
				$rows = $this->db->select('select id from sg_item where subsectionID = ? order by position', 'i', [&$subsection->id]);
				foreach ($rows as $row)
				{
					$sgItem = new StyleguideItem($this->db, $row['id']);
					$sgItem->read();
					$item = new StyleguideConfigTreeSectionItem();
					$item->id = $sgItem->id;
					$item->name = $sgItem->name;
					
					$subsection->items []= $item;
				}
				
				$rows = $this->db->select('select id from sg_subsection where parentSubsectionID = ? order by position', 'i', [&$subsection->id]);
				foreach ($rows as $row)
				{
					$sgSubSubsection = new StyleguideSubsection($this->db, $row['id']);
					$sgSubSubsection->read();
					$subSubsection = new StyleguideConfigTreeSectionItem();
					$subSubsection->id = $sgSubSubsection->id;
					$subSubsection->name = $sgSubSubsection->name;
					$subSubsection->enabled = $sgSubSubsection->enabled;
					
					$subsection->subsections []= $subSubsection;
					
					$rows = $this->db->select('select id from sg_item where subsectionID = ? order by position', 'i', [&$subSubsection->id]);
					foreach ($rows as $row)
					{
						$sgItem = new StyleguideItem($this->db, $row['id']);
						$sgItem->read();
						$item = new StyleguideConfigTreeSectionItem();
						$item->id = $sgItem->id;
						$item->name = $sgItem->name;
						
						$subSubsection->items []= $item;
					}
				}
			}
		}
		
		return $sections;
	}
	
	public function getTree()
	{
		$root = new StyleguideConfigTree();
		
		$colors = new StyleguideConfigTreeItem(null, 'Colors', 'Colors');
		$root->addChild($colors);
		
		$fonts = new StyleguideConfigTreeItem(null, 'Fonts', 'Fonts');
		$root->addChild($fonts);
		
		$uploads = new StyleguideConfigTreeItem(null, 'Uploads', 'Uploads');
		$root->addChild($uploads);
		
		$sections = new StyleguideConfigTreeItem(null, 'Sections', 'Sections');
		$root->addChild($sections);
		
		foreach ($this->getSections() as $section)
		{
			$sectionItem = new StyleguideConfigTreeItem($section->id, 'Section', $section->name, null, $section->enabled);
			$sections->addChild($sectionItem);
			
			foreach ($section->subsections as $index=>$subsection)
			{
				$subsectionItem = new StyleguideConfigTreeItem($subsection->id, 'Subsection', $subsection->name, self::romanNumeral($index + 1), $subsection->enabled);
				$sectionItem->addChild($subsectionItem);
				
				foreach ($subsection->items as $item)
				{
					$itemItem = new StyleguideConfigTreeItem($item->id, 'Item', $item->name);
					$subsectionItem->addChild($itemItem);
				}
				
				$subsubindexStr = 'a';
				foreach ($subsection->subsections as $subSubsection)
				{
					$subSubsectionItem = new StyleguideConfigTreeItem($subSubsection->id, 'Subsection', $subSubsection->name, $subsubindexStr++, $subSubsection->enabled);
					$subsectionItem->addChild($subSubsectionItem);
					
					foreach ($subSubsection->items as $item)
					{
						$itemItem = new StyleguideConfigTreeItem($item->id, 'Item', $item->name);
						$subSubsectionItem->addChild($itemItem);
					}
				}
			}
		}
		
		return $root;
	}
	
	private static function romanNumeral($integer) 
	{ 
		$table = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
		$return = ''; 
		while ($integer > 0) 
		{ 
			foreach ($table as $rom=>$arb) 
			{ 
				if ($integer >= $arb) 
				{ 
					$integer -= $arb; 
					$return .= $rom; 
					break; 
				} 
			} 
		} 
		
		return $return; 
	}
}

?>