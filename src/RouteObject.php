<?php

namespace Txiki\Router;

use Txiki\Router\RouteRegex;
use Txiki\Router\RouteException;

/**
 * RouteObject class
 */
class RouteObject
{
	/**
	 * route
	 *
	 * @var string
	 */
	public $route;

	/**
	 * Clousure
	 *
	 * @var \Clousure
	 */
	public $callback;

	/**
	 * Methods allowed
	 *
	 * @var array
	 */
	public $methods = [];

	/**
	 * Params
	 *
	 * @var array
	 */
	public $params = [];

	/**
	 * Params values
	 *
	 * @var array
	 */
	public $paramsValues = [];

	/**
	 * route object response
	 *
	 * @var mixed
	 */
	public $response = false;

	/**
	 * RouteObject constructor
	 *
	 * @param string    $route
	 * @param array     $methods
	 * @param \Clousure $callback
	 */
	public function __construct($route, $methods, $callback)
	{
		$this->route = $route;
		$this->methods = $methods;
		$this->callback = $callback;

		$params = [];

		preg_match_all('/{(.*?)}/', $route, $matches);

		if (count($matches) > 0) {
			foreach ($matches[1] as $key) {
				$params[$key] = RouteRegex::ALPHANUMERIC;
			}
		}

		if (count($params) > 0) {
			$this->params($params);
		}

	}

	/**
	 * Add params
	 *
	 * @param  array $params
	 *
	 * @return Txiki\Router\RouteObject
	 */
	public function params($params = [])
	{
		if (!is_array($params)) {
			throw new RouteException("Route filters need to be an array");
		}
		$this->params = $params;
		return $this;
	}

	/**
	 * Add param value
	 *
	 * @param mixed  $value value to add
	 * @param string $param param array key
	 *
	 * @return mixed 		added value
	 */
	public function addValue($value, $param)
	{
		return $this->paramsValues[$param] = $value;
	}
}
