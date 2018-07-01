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
	protected $currentUser;
	protected $model;
	protected $template;
	
	public function __construct(Model_base $model, $currentUser = null, Template $template = null)
	{
		$this->model = $model;
		$this->currentUser = $currentUser;
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
	
	public function index() {}
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

function MVCoutput($modelClass, $controllerClass, $viewClass = null, $templateFile = null, $currentUser = null, $action = null, $actionArgs = array())
{
	$model = new $modelClass;
	
	$controller = new $controllerClass($model);
	if ($action)
	{
		call_user_func_array([$controller, $action], $actionArgs);
	}
	else
	{
		call_user_func_array([$controller, 'index'], $actionArgs);
	}
	
	if ($viewClass)
	{
		$view;
		if ($templateFile)
		{
			$view = new $viewClass($model, $currentUser, $templateFile);
		}
		else
		{
			$view = new $viewClass($model, $currentUser);
		}
		
		return $view->output();
	}
}

?>