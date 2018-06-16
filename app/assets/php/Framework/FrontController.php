<?php

class Request
{
	public $currentUser;
	private $uri;
	private $params = array();
	
	public function __construct($uri)
	{
		$this->uri = $uri;
	}
	
	public function getUri()
	{
		return $this->uri;
	}
	
	public function getUriPath()
	{
		$path = parse_url($this->uri, PHP_URL_PATH);
		if (strlen($path) > 1 && substr($path, -1) == '/')
		{
			$path = substr($path, 0, strlen($path) - 1);
		}
		
		return $path;
	}
	
	public function getAction()
	{
		return $this->getParam('action');
	}
	
	public function setParam($key, $value)
	{
		$this->params[$key] = $value;
		return $this;
	}
	
	public function getParam($key)
	{
    	if (!isset($this->params[$key]))
		{
			return null;
    	}
    	return $this->params[$key];
  	}
 	
	public function getParams()
	{
    	return $this->params;
	}
}

class Route
{
	private $path;
	private $action;
	public $model;
	public $view;
	public $templateFile;
	public $controller;
	public $requiresAuth;
	public $requiredRole;
	
	public function __construct($path, $model, $view, $templateFile, $controller, $action = null, $requiresAuth = false, $requiredRole = null)
	{
		$this->path = $path;
		$this->model = $model;
		$this->view = $view;
		$this->templateFile = $templateFile;
		$this->controller = $controller;
		$this->action = $action;
		$this->requiresAuth = $requiresAuth;
		$this->requiredRole = $requiredRole;
	}
	
	public function match(Request $request)
	{
		return $this->path === $request->getUriPath() && $this->action === $request->getAction();
	}
}

class Router
{
	private $routes = array();
	
	public function __construct($routes = null)
	{
		if ($routes)
		{
			$this->addRoutes($routes);
		}
	}
	
	public function addRoute(Route $route)
	{
		$this->routes []= $route;
		return $this;
	}
	
	public function addRoutes(array $routes)
	{
		foreach ($routes as $route)
		{
			$this->addRoute($route);
		}
		return $this;
	}
	
	public function getRoutes()
	{
		return $this->routes;
	}
	
	public function route(Request $request)
	{
		foreach ($this->routes as $route)
		{
			if ($route->match($request))
			{
				return $route;
			}
		}
		
		throw new OutOfRangeException("No route matched the given URI.");
	}
}

class Dispatcher
{
	public function dispatch($route, $request)
	{
		echo MVCoutput($route->model, $route->controller, $route->view, $route->templateFile, $request->currentUser, $request->getAction());
	}
}

class FrontController
{
	private $router;
	private $dispatcher;
	
	public function __construct($router)
	{
		$this->router = $router;
		$this->dispatcher = new Dispatcher;
	}
 		
	public function run(Request $request)
	{
		$route = $this->router->route($request);
		$this->dispatcher->dispatch($route, $request);
	}
}

?>