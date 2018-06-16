<?php

function setReturnHeaders($httpStatusCode, $httpStatusMessage, $returnObj)
{
	header('HTTP/1.1 ' . $httpStatusCode . ' ' . $httpStatusMessage);
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode($returnObj));
}

function rrmdir($dir)
{
	if (is_dir($dir))
	{
		$objects = scandir($dir);
		foreach ($objects as $object)
		{
			if ($object != "." && $object != "..")
			{
				if (is_dir($dir. "/" . $object))
				{
					rrmdir($dir. "/" . $object);
				}
				else
				{
					unlink($dir. "/" . $object);
				}
			}
		}
     	rmdir($dir);
	}
}

?>
