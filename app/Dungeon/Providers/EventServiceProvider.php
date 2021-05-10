<?php

namespace Dungeon\Providers;

use Dungeon\Events\AfterAttack;
use Dungeon\Listeners\AfterAttackUsersDie;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AfterAttack::class => [
            AfterAttackUsersDie::class,
        ]
    ];

    /**
     * Register services.
     *we
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
