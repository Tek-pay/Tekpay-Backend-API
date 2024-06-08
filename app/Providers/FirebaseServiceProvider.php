<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $factory = (new Factory)
            ->withServiceAccount(__DIR__.'/firebase_credentials.json')
            ->withDatabaseUri('https://tekpay247.firebaseio.com');

        $this->app->instance('firebase', $factory);
    }
}
