<?php
namespace App\Modules\Managedfile\Providers;

use Caffeinated\Modules\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * The controller namespace for the module.
     *
     * @var string|null
     */
    protected $namespace = 'App\Modules\Managedfile\Http\Controllers';

    /**
     * Define your module's route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        //
    }

    /**
     * Define the routes for the module.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace
        ], function ($router) {
            require (config('modules.path') . '/Managedfile/Http/routes.php');
        });
    }
}
