<?php

namespace Txiki\Router;

use Txiki\Router\RouteRegex;
use Txiki\Router\RouteObject;
use Txiki\Router\RouteException;

/**
 * Route class
 */
class Route
{
    /**
     * Routes collection
     *
     * @var array
     */
	protected $routes = [];

    /**
     * Allowed routes http methods
     *
     * @var array
     */
	protected $httpMethods = [
        'get',
        'post',
        'put',
        'delete'
    ];

    /**
     * Routes table on array
     *
     * @return array
     */
	public function table()
    {
    	return $this->routes;
    }

    /**
     * Get http methods
     *
     * @return array
     */
    public function getHttpMethods()
    {
        return $this->httpMethods;
    }

    /**
     * Add new route
     *
     * @param string  $route    route to process
     * @param \Clousure $callback
     * @param string $types
     *
     * @return Txiki\Router\RouteObject
     */
    public function add( $route, $callback = false, $types = false )
    {

    	if(!$callback){
    		throw new RouteException("No route callback set");
    	}

    	if(!$types){
    		$types = implode('|',$this->httpMethods);
    	}

    	$types_to_check = explode('|', $types);

    	foreach ($types_to_check as $method) {

    		$this->checkMethodType($method);

    		if($this->getRouteMap($route,$method)){
    			throw new RouteException("Duplicate Route method defined");
    		}
    	}

    	return $this->routes[$route][] = new RouteObject(
            $route,
            $types_to_check ,
            $callback
        );

    }

    /**
     * Get map table for one route
     *
     * @param  string  $route   route to check
     * @param  mixed   $method  http method to check
     * @return mixed            return false, Txiki\Router\RouteObject, or array of RouteObject's
     */
    public function getRouteMap( $route , $method = false)
    {

    	if(array_key_exists($route, $this->routes)){

    		if(!$method){
    			return $this->routes[$route];
    		}else{

    			$this->checkMethodType($method);

	    		$routesObjects = $this->routes[$route];
	    		foreach ($routesObjects as $routeObject) {
	    			foreach ($routeObject->methods as $routeObjectMethod) {
	    				if($routeObjectMethod === $method){
	    					return $routeObject;
	    				}
	    			}
	    		}
	    		return false;
    		}
    	}
    	return false;
    }

    /**
     * Check http method allowed
     *
     * @param  string $method method name
     * @return mixed          true if allowed, throw RouteException if not
     */
    private function checkMethodType( $method )
    {
    	if(!in_array( strtolower( $method ), $this->httpMethods)){
    		throw new RouteException("Error Processing Route Type");
    	}
    	return true;
    }

    /**
     * Exec one route request
     *
     * @param  string $request
     * @param  string $method  method name
     *
     * @return mixed           return one Txiki\Router\RouteObject or false
     */
    public function exec( $request , $method )
    {

    	$method = strtolower( $method );

    	foreach ($this->routes as $key => $route) {

    		for ($i=0; $i < count($route); $i++) {

	    		if(in_array($method,$route[$i]->methods)){

		    		$search = $key;

		    		if($route[$i]->params){
			    		foreach ($route[$i]->params as $paramKey => $value) {
			    			$search = str_replace('{'.$paramKey.'}',$route[$i]->params[''.$paramKey.''], $search);
			    		}
		    		}

		    		$search = str_replace('/', '\/' ,$search);
		    		$search = '/^'.$search.'$/i';

		    		if(preg_match( $search , $request, $matches_values)){

		    			array_shift ( $matches_values );

		    			preg_match_all( '/{(.*?)}/', $key , $matches_vars  );
		    			$matches_vars = $matches_vars[1];

		    			for ($j=0; $j<count($matches_vars); $j++) {

		    			 	if(isset($route[$i]->params[$matches_vars[$j]])){

		    			 		if( isset ($matches_values[$j]) ) {
		    			 			$route[$i]->addValue( $matches_values[$j] , $matches_vars[$j]);
		    			 		}

		    			 	}

		    			}
		    			if(is_callable($route[$i]->callback)){
		    				$route[$i]->response = call_user_func_array($route[$i]->callback, $route[$i]->paramsValues);
		    			}else{
		    				$call = explode('.',$route[$i]->callback);
		    				$class = new $call[0];
		    				$route[$i]->response = call_user_func_array(array($class, $call[1]), $route[$i]->paramsValues);
		    			}

		    			return $route[$i];
		    		}
	    		}
    		}
    	}

    	return false;

    }

    /**
     * Magic method for add routes with get, post, put or any function name
     *
     * @param  string $name
     * @param  array $arguments
     *
     * @return Txiki\Router\RouteObject
     */
    public function __call($name, $arguments)
    {
        if($name=='any'){
            $name = false;
        }

        return $this->add($arguments[0] , $arguments[1], $name);
    }

}
