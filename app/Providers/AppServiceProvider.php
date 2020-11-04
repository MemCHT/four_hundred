<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        //favorite, ステータス表示のエイリアス定義
        Blade::component('components.favorite', 'favorite');
        Blade::component('components.status', 'status');
        Blade::component('components.article', 'article');
        Blade::component('components.comment', 'comment');
    }
}
