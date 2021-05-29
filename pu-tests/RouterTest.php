<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
class testMethod
{
	public $testedValue = '';

	public function testedMethod($params)
	{
		$this->testedValue = 'Done';
	}
}
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
	 * @dataProvider methodProvider
     */
	public function testRun(string $crazyMethod, string $httpMethod)
	{
		$testVar = '';

		$testFunc = function ($params) use (&$testVar)
		{
			$testVar = 'Done';
		};

		$this->router->addRoute($crazyMethod, '/testRun', $testFunc);
		$_SERVER['REQUEST_METHOD'] = $httpMethod;
		$_SERVER['REQUEST_URI'] = '/testRun';
		$this->router->run();
		if ($crazyMethod === $httpMethod)
			$this->assertEquals($testVar, 'Done');
		else
			$this->assertNotEquals($testVar, 'Done');
	}


	/**
     * @runInSeparateProcess
	 * @dataProvider methodProvider
     */
	public function testRunWithObjectMethod(string $crazyMethod, string $httpMethod)
	{
		$testObject = new testMethod();

		$this->router->addRoute($crazyMethod, '/testRunWithObject', [$testObject, 'testedMethod']);
		$_SERVER['REQUEST_METHOD'] = $httpMethod;
		$_SERVER['REQUEST_URI'] = '/testRunWithObject';
		$this->router->run();
		if ($crazyMethod === $httpMethod)
			$this->assertEquals($testObject->testedValue, 'Done');
		else
			$this->assertNotEquals($testObject->testedValue, 'Done');
	}

	public function methodProvider(): array
	{
		return [
			'get-get set' => [
				Crazy\Router::GET,
				'GET',
			],
			'post-post set' => [
				Crazy\Router::POST,
				'POST',
			],
			'put-put set' => [
				Crazy\Router::PUT,
				'PUT',
			],
			'patch-patch set' => [
				Crazy\Router::PATCH,
				'PATCH',
			],
			'delete-delete set' => [
				Crazy\Router::DELETE,
				'DELETE',
			],
			'get-post set' => [
				Crazy\Router::GET,
				'POST',
			],
			'get-put set' => [
				Crazy\Router::GET,
				'PUT',
			],
			'get-patch set' => [
				Crazy\Router::GET,
				'PATCH',
			],
			'get-delete set' => [
				Crazy\Router::GET,
				'DELETE',
			],
			'post-get set' => [
				Crazy\Router::POST,
				'GET',
			],
			'post-put set' => [
				Crazy\Router::POST,
				'PUT',
			],
			'post-patch set' => [
				Crazy\Router::POST,
				'PATCH',
			],
			'post-delete set' => [
				Crazy\Router::POST,
				'DELETE',
			],
			'put-get set' => [
				Crazy\Router::PUT,
				'GET',
			],
			'put-post set' => [
				Crazy\Router::PUT,
				'POST',
			],
			'put-patch set' => [
				Crazy\Router::PUT,
				'PATCH',
			],
			'put-delete set' => [
				Crazy\Router::PUT,
				'DELTE',
			],
			'patch-get set' => [
				Crazy\Router::PATCH,
				'GET',
			],
			'patch-post set' => [
				Crazy\Router::PATCH,
				'POST',
			],
			'patch-put set' => [
				Crazy\Router::PATCH,
				'PUT',
			],
			'patch-delete set' => [
				Crazy\Router::PATCH,
				'DELETE',
			],
			'delete-get set' => [
				Crazy\Router::DELETE,
				'GET',
			],
			'delete-post set' => [
				Crazy\Router::DELETE,
				'POST',
			],
			'delete-put set' => [
				Crazy\Router::DELETE,
				'PUT',
			],
			'delete-patch set' => [
				Crazy\Router::DELETE,
				'PATCH',
			],
		];
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