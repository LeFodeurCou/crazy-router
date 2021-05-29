<?php

namespace Crazy;

class Router
{
	const GET = 'GET';
	const POST = 'POST';
	const PUT = 'PUT';
	const PATCH = 'PATCH';
	const DELETE = 'DELETE';

	private array $routes = [];

	public function getAllRoutes(): array
	{
		return $this->routes;
	}

	public function addRoute(
		string $method,
		string $route,
		callable $callable,
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
		preg_match_all('#{([\s\S]+?)}#', $route, $matches);

		$this->routes[] = [
			'method'	=>	$method,
			'pattern'	=>	preg_replace($masks, $patterns, $route, 1),
			'callable'	=>	$callable,
			'name'		=>	$name,
			'params'	=>	$matches[1],
		];
	}

	public function run(callable $default = null)
	{
		$matches = [];
		if (isset($_SERVER['REQUEST_URI']))
			foreach ($this->routes as $route)
				if (preg_match_all('~^' . $route['pattern'] . '/?$~', $_SERVER['REQUEST_URI'], $matches))
				{
					if ($_SERVER['REQUEST_METHOD'] != $route['method'])
						return header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1').' 405 Method Not Allowed', true, 405);
					array_shift($matches);
					$params = [];
					foreach ($route['params'] as $param)
						$params[$param] = array_shift($matches)[0];
					$route['callable']($params);
					return;
				}
		if ($default)
			$default();
	}
}