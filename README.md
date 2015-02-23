# Txiki Router

Simple router for PHP

[![Author](http://img.shields.io/badge/author-@dieg0v-blue.svg?style=flat-square)](https://twitter.com/dieg0v)
[![Latest Version](https://img.shields.io/github/release/dieg0v/txiki-router.svg?style=flat-square)](https://github.com/dieg0v/txiki-router/releases)
[![Packagist Version](https://img.shields.io/packagist/v/txiki/txiki-router.svg?style=flat-square)](https://packagist.org/packages/txiki/txiki-router)

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/dieg0v/txiki-router/master.svg?style=flat-square)](https://travis-ci.org/dieg0v/txiki-router)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/dieg0v/txiki-router.svg?style=flat-square)](https://scrutinizer-ci.com/g/dieg0v/txiki-router/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/dieg0v/txiki-router.svg?style=flat-square)](https://scrutinizer-ci.com/g/dieg0v/txiki-router/?branch=master)

## Install

Via Composer

``` bash
$ composer require txiki/txiki-router
```

## Requirements

The following versions of PHP are supported by this version.

* PHP 5.4
* PHP 5.5
* PHP 5.6
* HHVM

## Documentation

Simple example:

``` php
// load composer autoload
require '../vendor/autoload.php';

use Txiki\Router\Route;

$r = new Route();

// add GET route
$r->get('/home', function(){
    return "GET Hello world!";
});

//tell router what you want to process, the route and the http method
$route = $r->exec( '/home', 'get');

if($route!==false){
	// example process response
	echo $route->response;
}else{
	// example response 404
	echo '404';
}
```

Add more routes:

``` php
// add POST route
$r->post('/home', function(){
    return "POST Hello world!";
});

// add DELETE route
$r->delete('/home', function(){
    return "DELETE Hello world!";
});

// add PUT route
$r->put('/home', function(){
    return "PUT Hello world!";
});
```

Wildcard and custom routes:
``` php
// add route to any http method
$r->any('/home', function(){
    return "Hello world! respond to any http method";
});

// custom http methods for one route
$r->add('/home', function(){
    return "Hello world! respond to custom http methods";
}, 'get|post');
```

Manage custom url params:
``` php
$r->any('/test-{id}/{name}/{n}', function($id, $name , $n){
    return 'Test: ' . $id .' '.$name.' '.$n;
})->params([
		// add your own regular expression to param or
		// 'use Txiki\Router\RouteRegex'
		'id' => RouteRegex::INT,
		'name' => RouteRegex::ALPHA,
		'n' => RouteRegex::INT
	]
);
```

Use class/method as callback:
``` php
class myClass{
    public function method1( $id, $name){
        return 'Hello world ' .$id . ' '. $name;
    }
}

$r->get('/user/{id}/{name}', 'myClass.method1');
```

Helper methods:
``` php
// get all established routes
$table = $r->table();

// get all routes for '/home'
$map = $r->getRouteMap('/home');

// get only POST route for '/home'
$map = $r->getRouteMap('/home', 'post');
```

## Testing

``` bash
$ vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/dieg0v/txiki-router/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Diego Vilariño](https://github.com/dieg0v)
- [All Contributors](https://github.com/dieg0v/txiki-router/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/dieg0v/txiki-router/blob/master/LICENSE.md) for more information.
