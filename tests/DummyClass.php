<?php

namespace Txiki\Router\Tests;

/**
 * DummyClass
 */

class DummyClass
{
	/**
	 * DummyClass method1
	 *
	 * @param  mixed $id
	 * @param  mixed $name
	 *
	 * @return string
	 */
    public function method1( $id, $name)
    {
        return 'Hello world ' .$id . ' '. $name;
    }
}
