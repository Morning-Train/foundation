<?php

namespace morningtrain\Themer;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use morningtrain\Janitor\Exceptions\JanitorException;
use morningtrain\Themer\Middleware\LoadTheme;
use morningtrain\Themer\Services\Themer;

class ThemerServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot() {
        // Register blade directives
        $this->registerBladeDirectives();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {

        // Publish files
        $this->publish();

        // Register themer service
        $this->app->singleton(Themer::class, function( $app ) {
            return new Themer();
        });

    }

    /**
     * Files to publish
     */
    public function publish() {

        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/themer.php'  => config_path('themer.php')

        ], 'config');

        // Publish gulp file
        $this->publishes([
            __DIR__ . '/../gulp/themer.js'  => base_path('gulp/themer.js')

        ], 'gulp');

    }

    /**
     * Blade directives
     */
    public function registerBladeDirectives() {
        $app = $this->app;
        $blade = $this->app->make('blade.compiler');

        // @act
        $blade->directive('do', function( $expression ) use( $app ) {

            // Fetch arguments
            $arguments = explode(',', str_replace(['(', ')', ' '], '', $expression));

            if (
                (count($arguments) === 0) ||
                !is_string($arguments[0]) ||
                (strlen($arguments[0]) === 0)
            ) {
                throw new JanitorException('Invalid action name passed to blade @act.');
            }

            // Stringify arguments
            $arguments = implode(', ', $arguments);

            return "<?php call_user_func_array([ app()->make('themer')->current(), 'do' ], [ $arguments ]); ?>";
        });
    }
}