<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.storefront', function ($view) {
            $view->with([
                'navCategories' => Category::where('is_active', true)->orderBy('name')->get(),
                'navBrands' => Brand::where('is_active', true)->orderBy('name')->get(),
            ]);
        });
    }
}
