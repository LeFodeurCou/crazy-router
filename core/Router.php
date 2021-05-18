<?php

namespace Crazy;

class Router
{
	const GET = 0;
	const POST = 1;
	const PUT = 2;
	const PATCH = 3;
	const DELETE = 4;

	private array $routes = [];

	function __construct()
	{
		
	}

	public function getAllRoutes(): array
	{
		return $this->routes;
	}

	public function addRoute(
		int $method,
		string $route = null,
		callable $callable = null,
		array $patterns = [],
		string $name = null
	)
	{
		$masks = [];
		foreach ($patterns as $pattern) 
			$masks[] = '#{[\s\S]+?}#';
		$this->routes[] = [
			'method'	=>	$method,
			'pattern'	=>	preg_replace($masks, $patterns, $route, 1),
			'callable'	=>	$callable,
			'name'		=>	$name,
		];
	}

	public function run()
	{
		if (isset($_SERVER['REQUEST_URI']))
			foreach ($this->routes as $route)
				if (preg_match('~^' . $route['pattern'] . '/?$~', $_SERVER['REQUEST_URI']))
					$route['callable']();
	}
}