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
		foreach ($patterns as &$pattern)
		{
			$masks[] = '#{([\s\S]+?)}#';
			$pattern = '(' . $pattern . ')';
		}
		
		$matches = [];
		$params = [];
		preg_match_all('#{([\s\S]+?)}#', $route, $matches);
		foreach ($matches[1] as $match)
			$params[$match] = null;

		$this->routes[] = [
			'method'	=>	$method,
			'pattern'	=>	preg_replace($masks, $patterns, $route, 1),
			'callable'	=>	$callable,
			'name'		=>	$name,
			'params'	=>	$params
		];
	}

	/** TODO change $route['callable']($route) for $route['callable']($param)
	 * and make $param with good corresponding args (like adding 'params' => preg_match in addRoute)
	*/
	public function run(callable $default = null)
	{
		$matches = [];
		if (isset($_SERVER['REQUEST_URI']))
			foreach ($this->routes as $route)
				if (preg_match_all('~^' . $route['pattern'] . '/?$~', $_SERVER['REQUEST_URI'], $matches))
				{
					array_shift($matches);
					foreach ($route['params'] as &$param)
						$param = array_shift($matches)[0];
					$route['callable']($route['params']) || exit;
				}
		if ($default)
			$default();
	}
}