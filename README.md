# Crazy-Router

## 🐘 A php based router. Simple and fast. 

> Crazy-Router is just a router.

It is designed to be as simple as possible and as faster as possible too.

-------------------
## 🔌 Installation

### Prerequisite

PHP >= 8.0
Composer >= 2.0

All requests must be redirected to your index.php (see Nginx or Apache configuration).

### Composer

`composer init`

`composer require lefodeurcou/crazy-router`

-------------------
## 💻 Usage

### Features

#### Add how many routes you want

```
public function addRoute(
		string $method,
		string $route,
		callable $callable,
		array $patterns = [],
		string $name = null
	)
```
> For the moment, this method is only a side effect method.

- **method** is http method represented by constants :
	- Crazy\Router::GET
	- Crazy\Router::POST
	- Crazy\Router::PUT
	- Crazy\Router::PATCH
	- Crazy\Router::DELETE
- **route** is the path after the domain name like `/`, `/path` or `/path/for/the/truth`
Routes can be parametrized like that : `/user/{id}` where `{id}` is an unknown value that can be getted back in callable (see below).
- **callable** is the function or the method that will be called if the route match the URL. Can be like :
	- `funtion($params) {}` or `function($params) use ($someVariable) {}`
	- `'functionName'`
	- `[$objectInstance, 'methodName']`
The callable must take one parameter : `$params` that contain all unknown values from the parametrized route.
To access these values you must use `$params` as associative array with keys that correspond to the route parameters, like that (see above at **route**):
		```
		if (isset($params['id']))
			echo $params['id'];
		```
- **patterns** is an array that have to contain one pattern for each route parameter, like that :
	```
	[
		'[0-9]+',
	]
	```
	If there is more patterns than parameters, they will be ingored.
	If there is less patterns than parameters, route will be ignored.
	> In php you can have a comma for last input of an array. It's ok.
- **name** is a string that allow you to retrive the route if you crawl the routes array given by `getAllRoutes` method.
It's the less usefull parameter for the moment, but in a next version it could be used to trigger the callable of a named route trougth an other route, by example.

#### Get all routes with ...

```
public function getAllRoutes()
```
Return an array that contains all route added earlier. Obviously 😎

#### Run, for a long time

```
public function run(callable $default = null)
```
Run the router, then it try to match a route with current URL. If it do, it run a corresponding callable. If it don't, it run `$default` callable if it's provided. That's all.

### Examples

#### Simple boilerplate

```

require_once __DIR__ . '/vendor/autoload.php';

use Crazy\Router;

$router = new Crazy\Router();

$router->addRoute(Crazy\Router::GET, '/', function () {echo 'Example';});

$router->run();

```

#### With function declared before, and one parameter

```

require_once __DIR__ . '/vendor/autoload.php';

use Crazy\Router;

$router = new Crazy\Router();

function example ($params)
{
	if (isset($params['id']))
    	echo $params['id'];
}

$router->addRoute(Crazy\Router::GET, '/user/{id}', 'example', [
	'[0-9]+',
]);

$router->run();

```

#### With a method from a class

```
require_once __DIR__ . '/vendor/autoload.php';

use Crazy\Router;

$router = new Crazy\Router();

class demo
{
	public function example() {
		echo 'Example';
	}
}

$router->addRoute(Crazy\Router::GET, '/', [new demo(), 'example'], [], 'Demo route');

$router->run();
```
-------------------
## 🔧 Devlopment

Unix environment is recommended and more again a linux distribution.

There is two bash script for units tests and benchmarks tests.

Except these, you can use what you want.

### Units Tests

It use PHPUnit for it.

There is a bash script `unit` to launch tests.

### Benchmarks

It use PHPBench for it.

There is a bash script `bench` to launch tests.

## 🔐 License

MIT

-------------------
## 📢 Last word

Don't forget, be crazy 💥💥💥