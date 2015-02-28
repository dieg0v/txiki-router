<?php

namespace Txiki\Router\Tests;

use Txiki\Router\Route;

/**
 * RouteExceptionTest class
 */
class RouteExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
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
     * Asserts route exception no callback
     *
     * @return void
     */
    public function testRouteExceptionNoCallback()
    {
        $this->setExpectedException('Txiki\Router\RouteException', 'No route callback set', 0);

        $this->router->add('/home');
    }

    /**
     * Asserts route exception
     *
     * @return void
     */
    public function testRouteExceptionDuplicateRoute()
    {
        $this->setExpectedException('Txiki\Router\RouteException', 'Duplicate Route method defined', 0);

        $this->router->get('/home', function () { return true; });
        $this->router->get('/home', function () { return false; });
    }

    /**
     * Asserts route exception check method type
     *
     * @return void
     */
    public function testRouteExceptionCheckMethodType()
    {
        $this->setExpectedException('Txiki\Router\RouteException', 'Error Processing Route Type', 0);

        $this->router->fail('/home', function () { return true; });
    }

    /**
     * Asserts route exception check method type on add method
     *
     * @return void
     */
    public function testRouteExceptionCheckMethodTypeOnAdd()
    {
        $this->setExpectedException('Txiki\Router\RouteException', 'Error Processing Route Type', 0);

        $this->router->add('/home', function () { return true; }, 'get|post|fail');
    }

    /**
     * Asserts route exception on add params to route object
     *
     * @return void
     */
    public function testRouteObjectExceptionAddParams()
    {
        $this->setExpectedException('Txiki\Router\RouteException', 'Route filters need to be an array', 0);

        $this->router->add('/home', function () { return true; }, 'get|post')->params('fail params');
    }
}
