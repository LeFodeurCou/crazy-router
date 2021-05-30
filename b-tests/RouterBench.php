<?php

use Crazy\Router;

function routeFunction () {}
/**
 * 
 */
class RouterBench
{

	private $router;

	public function makeRouter()
	{
		$this->router = new Crazy\Router();
	}

    /**
	 * @BeforeMethods("makeRouter")
     * @Revs(1)
     * @Iterations(1)
     */
	public function benchRouterOnce()
	{
		$this->router->addRoute(Crazy\Router::GET, '/{path}/test/{token}', function ($params)
		{
			echo 'This route is registered with pattern !<br />';
			if (isset($params['path']))
				echo $params['path'] . '<br />';
			if (isset($params['token']))
				echo $params['token'] . '<br />';
			if (isset($params['id']))
				echo $params['id'] . '<br />';
		}, [
			'[a-zA-Z]+',
			'[a-zA-Z0-9]+',
		]);
	}

    /**
	 * @BeforeMethods("makeRouter")
     * @Revs(100000)
     * @Iterations(10)
	 * @RetryThreshold(2.0)
     */
	public function benchRouter()
	{
		$this->router->addRoute(Crazy\Router::GET, '/{path}/test/{token}', function ($params)
		{
			echo 'This route is registered with pattern !<br />';
			if (isset($params['path']))
				echo $params['path'] . '<br />';
			if (isset($params['token']))
				echo $params['token'] . '<br />';
			if (isset($params['id']))
				echo $params['id'] . '<br />';
		}, [
			'[a-zA-Z]+',
			'[a-zA-Z0-9]+',
		]);
	}

	/**
	 * @BeforeMethods("makeRouter")
     * @Revs(100000)
     * @Iterations(10)
	 * @ParamProviders("provideRoutes")
	 * @RetryThreshold(2.0)
     */
	public function benchAddManyRoutes(array $params)
	{
		$this->router->addRoute(
			$params['method'],
			$params['route'],
			$params['callable'],
			$params['patterns'],
			$params['name'],
		);
	}

	/**
	 * @BeforeMethods("makeRouter")
     * @Revs(100000)
     * @Iterations(10)
	 * @RetryThreshold(2.0)
	 */ 
	public function benchRun()
	{
		$this->router->addRoute(
			Crazy\Router::GET,
			'/{path}/test/{token}',
			function ($params){},
			[
				'[a-zA-Z]+',
				'[a-zA-Z0-9]+',
			]
		);
		// $_SERVER['REQUEST_METHOD'] = $httpMethod;
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['REQUEST_URI'] = '/path/test/abc42';
		$this->router->run();
	}

	public function provideRoutes(): Generator
	{

		yield 'routeOne' => [
			'method'	=>	Crazy\Router::GET,
			'route'		=>	'/',
			'callable'	=>	'routeFunction',
			'patterns'	=>	[],
			'name'		=>	'home',
		];
		yield 'routeTwo' => [
			'method'	=>	Crazy\Router::GET,
			'route'		=>	'/{page}',
			'callable'	=>	'routeFunction',
			'patterns'	=>	[
				'[a-zA-Z0-9-]+'
			],
			'name'		=>	'pages',
		];
		yield 'routeThree' => [
			'method'	=>	Crazy\Router::GET,
			'route'		=>	'/users',
			'callable'	=>	'routeFunction',
			'patterns'	=>	[],
			'name'		=>	'getUserById',
		];
		yield 'routeFor' => [
			'method'	=>	Crazy\Router::GET,
			'route'		=>	'/user/{id_user}',
			'callable'	=>	'routeFunction',
			'patterns'	=>	[
				'[0-9]+'
			],
			'name'		=>	'getUserById',
		];
		yield 'routeFive' => [
			'method'	=>	Crazy\Router::POST,
			'route'		=>	'/user',
			'callable'	=>	'routeFunction',
			'patterns'	=>	[],
			'name'		=>	'postUser',
		];
		yield 'routeSix' => [
			'method'	=>	Crazy\Router::PUT,
			'route'		=>	'/user/{id_user}',
			'callable'	=>	'routeFunction',
			'patterns'	=>	[
				'[0-9]+'
			],
			'name'		=>	'putUserById',
		];
		yield 'routeSeven' => [
			'method'	=>	Crazy\Router::PATCH,
			'route'		=>	'/user/{id_user}',
			'callable'	=>	'routeFunction',
			'patterns'	=>	[
				'[0-9]+'
			],
			'name'		=>	'patchUserById',
		];
		yield 'routeSeven' => [
			'method'	=>	Crazy\Router::DELETE,
			'route'		=>	'/user/{id_user}',
			'callable'	=>	'routeFunction',
			'patterns'	=>	[
				'[0-9]+'
			],
			'name'		=>	'deleteUserById',
		];
	}
}

?>