<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\Account;
use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
            View::composer('admin.*', function ($view) {
                $adminId = session('admin_id');
                $admin = null;

                if (!empty($adminId)) {
                    $admin = Account::find($adminId);
                }

                $view->with('admin', $admin);
            });
        

    }
}
