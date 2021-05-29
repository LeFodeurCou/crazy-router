<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require_once $_SERVER['DOCUMENT_ROOT'] . 'core/Router.php';

	$router = new Crazy\Router();

	$basicRouteCallable = function ($params)
	{
		echo 'This route is registered !';
	};

	$patternRouteCallable = function ($params)
	{
		echo 'This route is registered with pattern !<br />';
		if (isset($params['path']))
			echo $params['path'] . '<br />';
		if (isset($params['token']))
			echo $params['token'] . '<br />';
		if (isset($params['id']))
			echo $params['id'] . '<br />';
	};

	$isNotARoute = function ()
	{
		echo '404 error';
	};

	$router->addRoute(Crazy\Router::GET, '/test', $basicRouteCallable);
	$router->addRoute(Crazy\Router::GET, '/', $basicRouteCallable);
	$router->addRoute(Crazy\Router::GET, '/{id}', $patternRouteCallable, [
		'[0-9]+',
	]);
	$router->addRoute(Crazy\Router::GET, '/{path}/test/{token}', $patternRouteCallable, [
		'[a-zA-Z]+',
		'[a-zA-Z0-9]+',
	]);
	$router->addRoute(Crazy\Router::GET, '/{some}/{test}', $patternRouteCallable, [
		'[0-9]+',
		'[a-zA-Z0-9]+',
	]);
	$router->addRoute(Crazy\Router::GET, '/{user}/{id}', $patternRouteCallable, [
		'[a-zA-Z]+',
		'[0-9]+',
	]);
	$router->addRoute(Crazy\Router::GET, '/users', $basicRouteCallable);
	$router->addRoute(Crazy\Router::POST, '/post', $basicRouteCallable);

	$router->run($isNotARoute);
?>