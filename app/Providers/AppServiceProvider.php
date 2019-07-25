<?php

namespace App\Providers;



use App\Menu;
use App\Social;

use App\GeneralSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        $data['general'] = GeneralSettings::first();
        $data['basic'] = GeneralSettings::first();
        $data['menus'] =  Menu::all();
        $data['social'] =  Social::all();

        View::share($data);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
