<?php

namespace Botble\Member\Providers;

use Botble\Member\Listeners\AddMemberTokenListener;
use Botble\Member\Listeners\UpdatedContentListener;
use Botble\Base\Events\UpdatedContentEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Authenticated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        Authenticated::class => [
            AddMemberTokenListener::class,
        ],
    ];
}
