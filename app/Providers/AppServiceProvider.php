<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
{
    // Register the 'files' binding here
    $this->app->singleton('files', function () {
        return new \Illuminate\Filesystem\Filesystem;
    });
}

    public function boot(): void
    {
        //
    }
}