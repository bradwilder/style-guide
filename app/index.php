<?php

$site_path = realpath(__DIR__);
define ('__SITE_PATH', $site_path);
define ('__ASSETS_PATH', __SITE_PATH . '/assets');

set_include_path(__SITE_PATH);
set_include_path(__ASSETS_PATH);

spl_autoload_register(null, false);
spl_autoload_extensions('.class.php');

function modelLoader($class)
{
	$file = __ASSETS_PATH . "/php/Model/$class.class.php";
	if (!file_exists($file))
	{
		return false;
	}
	
	include_once $file;
}

function viewLoader($class)
{
	$file = __ASSETS_PATH . "/php/View/$class.class.php";
	if (!file_exists($file))
	{
		return false;
	}
	
	include_once $file;
}

function controllerLoader($class)
{
	$file = __ASSETS_PATH . "/php/Controller/$class.class.php";
	if (!file_exists($file))
	{
		return false;
	}
	
	include_once $file;
}

function dataClassLoader($class)
{
	$file = __ASSETS_PATH . "/php/DataClasses/$class.class.php";
	if (!file_exists($file))
	{
		return false;
	}
	
	include_once $file;
}

spl_autoload_register('modelLoader');
spl_autoload_register('viewLoader');
spl_autoload_register('controllerLoader');
spl_autoload_register('dataClassLoader');

require_once __SITE_PATH . '/../vendor/autoload.php';

require_once __ASSETS_PATH . '/php/DB/Db.php';
require_once __ASSETS_PATH . '/php/DB/DataClassBase.php';
require_once __ASSETS_PATH . '/php/Framework/functions.php';
require_once __ASSETS_PATH . '/php/Framework/MVC.php';
require_once __ASSETS_PATH . '/php/Framework/FrontController.php';
require_once __ASSETS_PATH . '/php/phpAuth/Config.php';
require_once __ASSETS_PATH . '/php/phpAuth/Auth.php';
require_once __ASSETS_PATH . '/php/Framework/Auth/EmailDelegate.php';
require_once __ASSETS_PATH . '/php/Framework/Auth/SMSDelegate.php';
require_once __ASSETS_PATH . '/php/Framework/Auth/Authentication.php';

// Create the request
$request = new Request($_SERVER[REQUEST_URI]);

$uri;
if ($request->getUriPath() != '/')
{
	$uri = $request->getUriPath();
}

if (isset($_REQUEST['action']))
{
	$request->setParam('action', $_REQUEST['action']);
}

// Router initialization
$router = new Router();
$db = new PDO('sqlite:' . __ASSETS_PATH . '/php/Framework/routes.db') or die("cannot open the database");
foreach ($db->query('select * from routes') as $row)
{
	$route = new Route($row['path'], $row['model'], $row['view'], $row['templateFile'], $row['controller'], $row['action'], $row['requiresAuth'] == 1, $row['requiredRole']);
	foreach ($db->query('select argument, type from actionArgs where routeID = ' . $row['id'] . ' order by position') as $argRow)
	{
		$argument;
		$type = $argRow['type'];
		switch ($type)
		{
			case 'string':
				$argument = $argRow['argument'];
				break;
			case 'boolean':
				$argument = ($argRow['argument'] === 'true');
				break;
			case 'integer':
				$argument = intval($argRow['argument']);
				break;
		}
		$route->addActionArg($argument);
	}
	
	$router->addRoute($route);
}

// Find the route
$route;
try
{
	$route = $router->route($request);
}
catch (Exception $e)
{
	$auth = new PHPAuth\Authentication(new Db());
	$currentUser = $auth->authenticate(true, $uri);
	if ($currentUser)
	{
		echo MVCoutput("PageModel", "PageController", "PageView", "Page.template.php", $currentUser, null, array('404 - Page Not Found!', 'notFound', true));
		return;
	}
}

// Auth
if ($route->requiresAuth)
{
	$auth = new PHPAuth\Authentication(new Db());
	$request->currentUser = $auth->authorize(true, $route->requiredRole, $uri);
}

// Respond to request
try
{
	$frontController = new FrontController($router);
	$frontController->run($request);
}
catch (Exception $e)
{
	echo $e->getMessage();
}

?>