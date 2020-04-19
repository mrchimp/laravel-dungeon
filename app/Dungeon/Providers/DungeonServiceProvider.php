<?php

namespace Dungeon\Providers;

use Illuminate\Support\ServiceProvider;

class DungeonServiceProvider extends ServiceProvider
{
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
        $this->app['router']->group(['prefix' => 'dungeon', 'middleware' => 'web'], function ($router) {
            $router->get('/', '\Dungeon\Http\Controllers\HomeController@index');
        });

        $this->app['router']->group(['prefix' => 'dungeon', 'middleware' => ['web', 'auth']], function ($router) {
            $router->post('cmd', '\Dungeon\Http\Controllers\CmdController@run');
        });
    }
}
