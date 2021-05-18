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

	public function addRoute(int $method, string $route = null, callable $callable = null, array $patterns = [], string $name = null)
	{
		$this->routes[] = [
			'method'	=>	$method,
			'route'		=>	preg_replace(['#{[\s\S]*?}#'], $patterns, $route),
			'callable'	=>	$callable,
			'name'		=>	$name,
		];
	}
}