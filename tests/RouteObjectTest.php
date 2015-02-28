<?php

namespace Txiki\Router\Tests;

use Txiki\Router\RouteObject;

/**
 * RouteObjectTest test class
 */
class RouteObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Route Object
     *
     * @var Txiki\Router\RouteObject
     */
    protected $routeObject;

    /**
     * Set up test
     *
     * @return void
     */
    protected function setUp()
    {
        $this->routeObject = new RouteObject(
            '/home',
            ['get', 'post'],
            function () { return 'ok'; }
        );
    }

    /**
     * Asserts route object constructor
     *
     * @return void
     */
    public function testConstructor()
    {
        $this->assertInstanceOf('Txiki\Router\RouteObject', $this->routeObject);

        $this->assertEquals(true, in_array('get', $this->routeObject->methods));

        $this->assertInstanceOf('Closure', $this->routeObject->getCallable());
    }

    /**
     * Asserts route object params
     *
     * @return void
     */
    public function testParams()
    {
        $this->routeObject->params([
            'a'=>'1',
            'b'=>'2'
        ]);

        $this->assertInstanceOf('Txiki\Router\RouteObject', $this->routeObject);

        $this->assertEquals(count($this->routeObject->params), 2);
    }

    /**
     * Asserts route object add value
     *
     * @return void
     */
    public function testAddValue()
    {
        $this->assertEquals(999, $this->routeObject->addValue(999, 'key'));
    }
}
