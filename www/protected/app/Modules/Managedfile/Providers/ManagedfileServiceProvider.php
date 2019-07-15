<?php
namespace App\Modules\Managedfile\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Caffeinated\Modules\Support\ServiceProvider;

class ManagedfileServiceProvider extends ServiceProvider
{

    /**
     * Register the Managedfile module service provider.
     *
     * This service provider is a convenient place to register your modules
     * services in the IoC container. If you wish, you may make additional
     * methods or service providers to keep the code more focused and granular.
     *
     * @return void
     */
    public function register()
    {
        App::register('App\Modules\Managedfile\Providers\RouteServiceProvider');

        Lang::addNamespace('managedfile', realpath(__DIR__ . '/../Resources/Lang'));
        View::addNamespace('managedfile', realpath(__DIR__ . '/../Resources/Views'));
    }

    /**
     * Bootstrap the application events.
     *
     * Here you may register any additional middleware provided with your
     * module with the following addMiddleware() method. You may pass in
     * either an array or a string.
     *
     * @return void
     */
    public function boot()
    {
        // $this->addMiddleware('');
    }

    /**
     * Additional Compiled Module Classes
     *
     * Here you may specify additional classes to include in the compiled file
     * generated by the `artisan optimize` command. These should be classes
     * that are included on basically every request into the application.
     *
     * @return array
     */
    public static function compiles()
    {
        $basePath = realpath(__DIR__ . '/../');

        return [
            // $basePath.'/example.php',
        ];
    }
}
