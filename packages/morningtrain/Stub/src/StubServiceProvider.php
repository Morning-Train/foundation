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
        $this->publish();
        $this->app->singleton('stub', Stub::class);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {
        //
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