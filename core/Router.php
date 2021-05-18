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
		echo $_SERVER['PHP_SELF'];
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
			'route'		=>	preg_replace($masks, $patterns, $route, 1),
			'callable'	=>	$callable,
			'name'		=>	$name,
		];
	}
}