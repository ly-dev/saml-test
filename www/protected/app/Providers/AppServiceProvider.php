<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Services\BladeUtilService;
use App\Services\FacebookService;
use App\Services\Saml2Service;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // binding services to service container
        $this->app->singleton(BladeUtilService::class, function () {
            return new BladeUtilService();
        });

        $this->app->singleton(FacebookService::class, function () {
            return new FacebookService();
        });

        $this->app->singleton(Saml2Service::class, function () {
            return new Saml2Service();
        });

        // add blade directive
        Blade::directive('toJsData', function ($expression) {
            return "<?php echo (isset{$expression} && (is_object{$expression} || is_array{$expression}) ? json_encode{$expression} : {$expression}); ?>";
        });

        Validator::extend('daterange', function ($attribute, $value, $parameters, $validator) {
            $parts = explode(" - ", $value);
            if (empty($parts[0]) || empty($parts[1])) {
                return false;
            }
            if ($this->checkDateFormat($parts[0], 'd/m/Y G:i') && $this->checkDateFormat($parts[1], 'd/m/Y G:i')) {
                return true;
            }
            return false;
        });

        Validator::extend('end_date', function ($attribute, $value, $parameters, $validator) {
            $parts = explode(" - ", $value);
            if (strtotime(str_replace("/", "-", $parts[1])) > time()) {
                return true;
            }
            return false;
        });

        Validator::extend('daterange_overlap', function ($attribute, $value, $parameters, $validator) {
            return false;
        });
    }

    private function checkDateFormat($value, $format)
    {
        return date($format, strtotime(str_replace("/", "-", $value))) == $value;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
