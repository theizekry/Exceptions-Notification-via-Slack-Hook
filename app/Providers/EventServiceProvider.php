<?php

namespace App\Providers;

use App\Events\Exceptions\ExceptionEvent;
use App\Listeners\Exceptions\PushNotificationToSlackListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        /*  SEND PUSH NOTIFICATION VIA SLACK API WHEN ANY EXCEPTION HAS BEEN OCCURRED  */

        ExceptionEvent::class => [
            PushNotificationToSlackListener::class
        ]

        /*  SEND PUSH NOTIFICATION VIA SLACK API WHEN ANY EXCEPTION HAS BEEN OCCURRED  */

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
