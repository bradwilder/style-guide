<?php

class Model_base
{
	protected $db;
	
	public function __construct()
	{
		$this->db = new Db();
    }
}

class View_base
{
	public $currentUser;
	protected $model;
	protected $template;
	
	public function __construct(Model_base $model, Template $template = null)
	{
        $this->model = $model;
		$this->template = $template;
    }
	
	public function output()
	{
		if ($this->currentUser)
		{
			$this->template->currentUser = $this->currentUser;
		}
		
		return $this->template->output();
	}
}

class Controller_base
{
	protected $model;
	
	public function __construct(Model_base $model)
	{
        $this->model = $model;
    }
	
	public function index()
	{
		
	}
}

class Template
{
	private $template;
	private $params = array();
	
	function __construct($template)
	{
		$this->template = $template;
	}
	
	public function output()
	{
		// Load variables
		foreach ($this->params as $key => $value)
		{
			$$key = $value;
		}
		
		ob_start();
		require($this->template);
		return ob_get_clean();
	}
	
	public function __set($key, $value)
	{
		$this->params[$key] = $value;
	}
}

function MVCoutput($modelClass, $controllerClass, $viewClass = null, $templateFile = null, $currentUser = null, $action = null)
{
	$model = new $modelClass;
	
	$controller = new $controllerClass($model);
	if ($action)
	{
		$controller->$action();
	}
	else
	{
		$controller->index();
	}
	
	if ($viewClass)
	{
		$view;
		if ($templateFile)
		{
			$view = new $viewClass($model, $templateFile);
		}
		else
		{
			$view = new $viewClass($model);
		}
		if ($currentUser)
		{
			$view->currentUser = $currentUser;
		}
		
		return $view->output();
	}
}

?>