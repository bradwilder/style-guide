<?php

class PageFooterModel extends Model_base
{
	public $code;
	
	public function __construct(string $code)
	{
		$this->code = $code;
	}
}

?>