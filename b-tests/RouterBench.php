<?php

use Crazy\Router;

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
     * @Revs(1000)
     * @Iterations(5)
     */

	// public function benchOne()
	// {
	// 	for ($i = 0; $i < 10000; $i++)
	// 		continue;
	// }

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
     * @Iterations(50)
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
}

?>