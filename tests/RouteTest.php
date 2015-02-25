<?php

namespace Txiki\Router\Tests;

use Txiki\Router\Route;
use Txiki\Router\RouteRegex;

/**
 * RouteTest class
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Route
	 *
	 * @var Txiki\Router\Route
	 */
	protected $router;

	/**
	 * Set up test
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->router = new Route();
	}

	/**
	 * Asserts routes table
	 *
	 * @return void
	 */
    public function testTable()
    {
        $this->assertEquals($this->router->table(), []);

        $this->router->add( '/home', function(){
    		return "ok";
    	}, 'get|post');

    	$this->assertEquals(count($this->router->table()), 1);
    }

    /**
	 * Asserts routes get http methods
	 *
	 * @return void
	 */
    public function testGetHttpMethods()
    {
    	$this->assertInternalType('array', $this->router->getHttpMethods());

    	$this->assertEquals(count($this->router->getHttpMethods()), 4);
    }

	/**
	 * Asserts get route map
	 *
	 * @return void
	 */
    public function testGetRouteMap()
    {
		$r = $this->router->add( '/home', function(){
			return "ok";
		}, 'get|post');

		$this->assertInstanceOf('Txiki\Router\RouteObject', $this->router->getRouteMap('/home', 'post'));

		$this->assertInstanceOf('Txiki\Router\RouteObject', $this->router->getRouteMap('/home', 'get'));

		$this->assertInstanceOf('Txiki\Router\RouteObject', $this->router->getRouteMap('/home')[0]);

		$this->assertEquals($this->router->getRouteMap('/fail'), false);

		$this->assertEquals($this->router->getRouteMap('/home','put'), false);

		$this->assertInternalType('array',$this->router->getRouteMap('/home'));
    }

	/**
	 * Asserts routes add
	 *
	 * @return void
	 */
    public function testAdd()
	{
		$r = $this->router->add( '/home', function(){
			return "ok";
		}, 'get|post');

		$this->assertInstanceOf('Txiki\Router\RouteObject', $r);

		$this->assertEquals($this->router->exec('/home', 'post')->response, 'ok');

		$this->assertEquals($this->router->exec('/home', 'get')->response, 'ok');

		$this->assertEquals($this->router->exec('/fail', 'get'), false);

    }

	/**
	 * Asserts routes exec
	 *
	 * @return void
	 */
    public function testExec()
    {
    	$r = $this->router->any('/any', function(){ return 'any'; });

    	$this->assertEquals($this->router->exec('/fail', 'get'), false);

    	$this->assertEquals($this->router->exec('/any', 'get')->response, 'any');
    	$this->assertEquals($this->router->exec('/any', 'post')->response, 'any');
    }

	/**
	 * Asserts routes call
	 *
	 * @return void
	 */
    public function testCall()
    {
		$r = $this->router->put('/put', function(){ return 'put'; });
		$this->assertEquals($r->methods[0], 'put');

		$r = $this->router->get('/get', function(){ return 'get'; });
		$this->assertEquals($r->methods[0], 'get');

		$r = $this->router->post('/post', function(){ return 'post'; });
		$this->assertEquals($r->methods[0], 'post');

		$r = $this->router->delete('/delete', function(){ return 'delete'; });
		$this->assertEquals($r->methods[0], 'delete');

    }

	/**
	 * Asserts routes call "any"
	 *
	 * @return void
	 */
    public function testCallAny()
    {
    	$r = $this->router->any('/any', function(){ return 'any'; });
    	$this->assertEquals(count($r->methods), count($this->router->getHttpMethods()));
    }

	/**
	 * Asserts route with params
	 *
	 * @return void
	 */
    public function testWithParams(){

    	$this->router->any('/test-{id}/{name}/{n}', function($id, $name , $n){
		    return 'Test: ' . $id .' '.$name.' '.$n;
		})->params([
				'id' => RouteRegex::INT,
				'name' => RouteRegex::ALPHA,
				'n' => RouteRegex::INT
			]
		);

		$this->assertEquals($this->router->exec('/test-1/myname/99', 'post')->response, 'Test: 1 myname 99');

    }

	/**
	 * Asserts route with external class
	 *
	 * @return void
	 */
    public function testExecClass(){

    	$this->router->get('/user/{id}/{name}', 'Txiki\Router\Tests\DummyClass::method1');

		$this->assertEquals($this->router->exec('/user/1/myname', 'get')->response, 'Hello world 1 myname');

    }

	/**
	 * Asserts route with external class and static method
	 *
	 * @return void
	 */
    public function testExecClassStaticMethod(){

    	$this->router->get('/user/{id}/{name}', 'Txiki\Router\Tests\DummyClass::method2');

		$this->assertEquals($this->router->exec('/user/1/myname', 'get')->response, 'Hello world 1 myname');

    }

}
