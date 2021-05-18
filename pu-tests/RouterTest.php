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
		$arraySrc[$lastIndex]['route'] = '';
		$arraySrc[$lastIndex]['callable'] = null;
		$arraySrc[$lastIndex]['name'] = 'nameTest';
		$arrayDest = $this->router->getAllRoutes();
		$this->assertNotEquals($arraySrc[$lastIndex]['method'], $arrayDest[$lastIndex]['method']);
		$this->assertNotEquals($arraySrc[$lastIndex]['route'], $arrayDest[$lastIndex]['route']);
		$this->assertNotEquals($arraySrc[$lastIndex]['callable'], $arrayDest[$lastIndex]['callable']);
		$this->assertNotEquals($arraySrc[$lastIndex]['name'], $arrayDest[$lastIndex]['name']);
	}

	/**
	 * @dataProvider addManyRouteProvider
	 */
	public function testGetAllRoutesResultIsGood(
		int $method,
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
			$this->assertEquals($arraySrc[$lastIndex]['route'], $expected['route']);
			$this->assertEquals($arraySrc[$lastIndex]['callable'], $expected['callable']);
			$this->assertEquals($arraySrc[$lastIndex]['name'], $expected['name']);
		}
	}

	/**
	 * @dataProvider addManyRouteProvider
	 */
	public function testIsAddRouteWorks(
		int $method,
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
					'route'		=>	'/',
					'callable'	=>	function () {},
					'name'		=>	'testName',
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
					'route'		=>	'/',
					'callable'	=>	function () {},
					'name'		=>	'testName',
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
					'route'		=>	'/',
					'callable'	=>	function () {},
					'name'		=>	'testName',
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
					'route'		=>	'/',
					'callable'	=>	function () {},
					'name'		=>	'testName',
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
					'route'		=>	'/',
					'callable'	=>	function () {},
					'name'		=>	'testName',
				]
			],
		];
	}
}