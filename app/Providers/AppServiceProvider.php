<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
        
        if (env("DB_SQL_DEBUG_LOG")) {
            \DB::listen(function ($query) {
                \Log::debug("DB: " . $query->sql . " [" .  implode(",", $query->bindings) . "]");
            });
        }
    }
}
