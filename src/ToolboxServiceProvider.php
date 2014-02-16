<?php namespace Impleri\Toolbox;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ToolboxServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->package('impleri/toolbox');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        App::bind(
            'toolbox.commands.controllers',
            function () {
                return new \Impleri\Toolbox\Commands\ControllersCommand();
            }
        );

        App::bind(
            'toolbox.commands.routes',
            function () {
                return new \Impleri\Toolbox\Commands\RoutesCommand();
            }
        );

        App::bind(
            'toolbox.commands.build',
            function () {
                return new \Impleri\Toolbox\Commands\BuildCommand();
            }
        );

        $this->commands(
            [
                'toolbox.commands.controllers',
                'toolbox.commands.routes',
                'toolbox.commands.build'
            ]
        );

        // Subscribe our own commands to toolbox.compile
        Event::listen('toolbox.build', function ($app) {
            $app->call('toolbox:routes');
            $app->call('toolbox:controllers');
        });
    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return [
            'toolbox.commands.controllers',
            'toolbox.commands.routes',
            'toolbox.commands.build'
        ];
    }
}