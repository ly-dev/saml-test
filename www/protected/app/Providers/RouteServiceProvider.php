<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
		$router->pattern('date_from', '([0-9]{4})-([0-1][0-9])-([0-3][0-9])(\s([0-1][0-9]|[2][0-3]):([0-5][0-9]):([0-5][0-9]))?');
		$router->pattern('date_to', '([0-9]{4})-([0-1][0-9])-([0-3][0-9])(\s([0-1][0-9]|[2][0-3]):([0-5][0-9]):([0-5][0-9]))?');

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
