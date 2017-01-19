<?php

namespace morningtrain\Stub;

use Illuminate\Support\ServiceProvider;
use morningtrain\Stub\Services\Stub;

class StubServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot() {

    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {
        // Publish files
        $this->publish();

        // Register service
        $this->app->singleton(Stub::class, function() {
            return new Stub();
        });
    }

    /**
     * Files to publish
     */
    public function publish() {

        // Publish stubs
        $this->publishes([
            __DIR__ . '/../resources/stubs' => base_path('resources/stubs')

        ], 'stubs');

    }
}