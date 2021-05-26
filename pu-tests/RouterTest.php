<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
	private $router;

	/**
	 * @before
	 */
	public function makeRouter()
	{
		$this->router = new Crazy\Router();
	}

	public function testIsInstanceOfRouter()
	{
		$this->assertInstanceOf(Crazy\Router::class, $this->router);
	}

	public function testGetAllRoutesResultIsArray()
	{
		$this->assertIsArray($this->router->getAllRoutes());
	}

	public function testGetAllRoutesResultIsImmutable()
	{
		$this->router->addRoute(\Crazy\Router::GET, '/', function() {}, [], 'testName');
		$arraySrc = $this->router->getAllRoutes();
		$lastIndex = count($arraySrc) - 1;
		$arraySrc[$lastIndex]['method'] = Crazy\Router::POST;
		$arraySrc[$lastIndex]['pattern'] = '';
		$arraySrc[$lastIndex]['callable'] = null;
		$arraySrc[$lastIndex]['name'] = 'nameTest';
		$arrayDest = $this->router->getAllRoutes();
		$this->assertNotEquals($arraySrc[$lastIndex]['method'], $arrayDest[$lastIndex]['method']);
		$this->assertNotEquals($arraySrc[$lastIndex]['pattern'], $arrayDest[$lastIndex]['pattern']);
		$this->assertNotEquals($arraySrc[$lastIndex]['callable'], $arrayDest[$lastIndex]['callable']);
		$this->assertNotEquals($arraySrc[$lastIndex]['name'], $arrayDest[$lastIndex]['name']);
	}

	/**
	 * @dataProvider addManyRouteProvider
	 */
	public function testIsAddRouteWorks(
		string $method,
		string $route = null,
		callable $callable = null,
		array $patterns = [],
		string $name = null,
		array $expected = []
	)
	{
		$this->router->addRoute($method, $route, $callable, $patterns, $name);
		$this->assertNotEmpty($this->router->getAllRoutes());
	}

	/**
	 * @dataProvider addManyRouteProvider
	 */
	public function testGetAllRoutesResultIsGood(
		string $method,
		string $route = null,
		callable $callable = null,
		array $patterns = [],
		string $name = null,
		array $expected = []
	)
	{
		$this->router->addRoute($method, $route, $callable, $patterns, $name);
		$arraySrc = $this->router->getAllRoutes();
		$lastIndex = count($arraySrc) - 1;
		
		if ($expected)
		{
			/** TODO assertEquals are not always the good assertion (function and array at least) */
			$this->assertEquals($arraySrc[$lastIndex]['method'], $expected['method']);
			$this->assertEquals($arraySrc[$lastIndex]['pattern'], $expected['pattern']);
			$this->assertEquals($arraySrc[$lastIndex]['callable'], $expected['callable']);
			$this->assertEquals($arraySrc[$lastIndex]['name'], $expected['name']);
			$this->assertTrue($arraySrc[$lastIndex]['params'] === $expected['params']);
		}
	}

	/**
     * @runInSeparateProcess
     */
	public function testRun()
	{
		$testVar = '';

		$testFunc = function ($params) use (&$testVar)
		{
			$testVar = 'Done';
		};

		$this->router->addRoute(Crazy\Router::GET, '/testRun', $testFunc);
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['REQUEST_URI'] = '/testRun';
		$this->router->run();
		$this->assertEquals($testVar, 'Done');
	}

	/**
     * @runInSeparateProcess
     */
	public function testRunMethodNotAllowed()
	{
		$testVar = '';

		$testFunc = function ($params) use (&$testVar)
		{
			$testVar = 'Done';
		};

		$this->router->addRoute(Crazy\Router::GET, '/testRun', $testFunc);
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_SERVER['REQUEST_URI'] = '/testRun';
		$this->router->run();
		$this->assertNotEquals($testVar, 'Done');
	}

	public function addManyRouteProvider(): array
	{
		return [
			'get set' => [
				Crazy\Router::GET,
				'/',
				function () {},
				[],
				'testName',
				[
					'method'	=>	Crazy\Router::GET,
					'pattern'	=>	'/',
					'callable'	=>	function () {},
					'name'		=>	'testName',
					'params'	=>	[],
				]
			],
			'post set' => [
				Crazy\Router::POST,
				'/',
				function () {},
				[],
				'testName',
				[
					'method'	=>	Crazy\Router::POST,
					'pattern'	=>	'/',
					'callable'	=>	function () {},
					'name'		=>	'testName',
					'params'	=>	[],
				]
			],
			'put set' => [
				Crazy\Router::PUT,
				'/',
				function () {},
				[],
				'testName',
				[
					'method'	=>	Crazy\Router::PUT,
					'pattern'	=>	'/',
					'callable'	=>	function () {},
					'name'		=>	'testName',
					'params'	=>	[],
				]
			],
			"patch set" => [
				Crazy\Router::PATCH,
				'/',
				function () {},
				[],
				'testName',
				[
					'method'	=>	Crazy\Router::PATCH,
					'pattern'	=>	'/',
					'callable'	=>	function () {},
					'name'		=>	'testName',
					'params'	=>	[],
				]
			],
			'delete set' => [
				Crazy\Router::DELETE,
				'/',
				function () {},
				[],
				'testName',
				[
					'method'	=>	Crazy\Router::DELETE,
					'pattern'	=>	'/',
					'callable'	=>	function () {},
					'name'		=>	'testName',
					'params'	=>	[],
				]
			],
			'get pattern set 01' => [
				Crazy\Router::GET,
				'/{type}/{id}',
				function () {},
				[
					'[a-zA-Z]+',
					'[1-9]+'
				],
				'testName',
				[
					'method'	=>	Crazy\Router::GET,
					'pattern'	=>	'/([a-zA-Z]+)/([1-9]+)',
					'callable'	=>	function () {},
					'name'		=>	'testName',
					'params'	=>	[
						'type',
						'id',
					],
				]
			],
			'get pattern set 02' => [
				Crazy\Router::GET,
				'/{type}/test/{id}',
				function () {},
				[
					'[a-zA-Z]+',
					'[1-9]+'
				],
				'testName',
				[
					'method'	=>	Crazy\Router::GET,
					'pattern'	=>	'/([a-zA-Z]+)/test/([1-9]+)',
					'callable'	=>	function () {},
					'name'		=>	'testName',
					'params'	=>	[
						'type',
						'id',
					],
				]
			],
		];
	}
}