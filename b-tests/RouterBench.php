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

	public function benchOne()
	{
		for ($i = 0; $i < 10000; $i++)
			continue;
	}


    /**
	 * @BeforeMethods("makeRouter")
     * @Revs(1000)
     * @Iterations(5)
     */
	public function benchRouter()
	{
		// Crazy\router('URL1')('URL2')('URL3')();
	}
}

?>