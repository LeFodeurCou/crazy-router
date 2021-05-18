<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require_once $_SERVER['DOCUMENT_ROOT'] . 'core/Router.php';

	$router = new Crazy\Router();

	$basicRouteCallable = function ()
	{
		echo 'This route is registered !';
	};

	$patternRouteCallable = function ()
	{
		echo 'This route is registered with pattern !';
	};

	$router->addRoute(Crazy\Router::GET, '/test', $basicRouteCallable);
	$router->addRoute(Crazy\Router::GET, '/', $basicRouteCallable);
	$router->addRoute(Crazy\Router::GET, '/{id}', $patternRouteCallable, [
		'[0-9]+'
	]);$router->addRoute(Crazy\Router::GET, '/{path}/test/{token}', $patternRouteCallable, [
		'[a-zA-Z]+',
		'[a-zA-Z0-9]+'
	]);

	$router->run();
?>