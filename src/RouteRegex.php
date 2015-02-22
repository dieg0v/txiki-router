<?php

namespace Txiki\Router;

/**
 * RouteRegex class
 */
class RouteRegex
{
	/**
	 * ANY regex constant
	 */
	const ANY = "([^/]+?)";

	/**
	 * INT regex constant
	 */
	const INT = "([0-9]+?)";

	/**
	 * ALPHA regex constant
	 */
	const ALPHA = "([a-zA-Z_-]+?)";

	/**
	 * ALPHANUMERIC regex constant
	 */
	const ALPHANUMERIC = "([0-9a-zA-Z_-]+?)";
}
