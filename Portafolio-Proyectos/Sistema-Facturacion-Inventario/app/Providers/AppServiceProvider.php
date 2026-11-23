<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('livewire/update', $handle)
                ->middleware(['web']);
        });
    }
}