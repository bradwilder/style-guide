<?php

class StyleguideItemColumns
{
	public $xs;
	public $sm;
	public $md;
	public $lg;
	
	public function __construct($xs, $sm, $md, $lg)
	{
		$this->xs = $xs;
		$this->sm = $sm;
		$this->md = $md;
		$this->lg = $lg;
	}
}

?>