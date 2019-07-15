<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Aacotroneo\Saml2\Events\Saml2LoginEvent::class => [
            \App\Listeners\Saml2LoginEventListener::class,
        ],
        \Aacotroneo\Saml2\Events\Saml2LogoutEvent::class => [
            \App\Listeners\Saml2LogoutEventListener::class,
        ]
    ]; 

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
