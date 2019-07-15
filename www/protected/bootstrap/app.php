<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

// set the public path
$app->bind('path.public', function() {
	return __DIR__ . '/../..';
});

// @TODO move to proper place for below app helper functions definition
if (! function_exists('t')) {
	/**
	 * Translate the give message with args and options
	 * according to the drupal way.
	 *
	 * @param string $string
	 * @param array $args
	 * @param array $options
	 */
	function t($string, array $args = array(), array $options = array())
	{
		if (is_null($string)) {
			return null;
		}

		//@TODO, use trans() for localization
		if (empty($args)) {
			return $string;
		}

		return str_replace(array_keys($args), array_values($args), $string);
	}
}

return $app;
