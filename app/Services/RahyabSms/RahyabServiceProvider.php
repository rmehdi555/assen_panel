<?php

namespace App\Services\RahyabSms;

use Illuminate\Support\ServiceProvider;

class RahyabServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register(): void
    {
        $this->app->singleton('Rahyabsms', function () {
            return new RahyabService();
        });
    }
}
