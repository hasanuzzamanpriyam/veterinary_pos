<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // nothing is here
    }

    public function boot(): void
    {
        if (app()->environment('production')) {

            URL::forceScheme('https');

        }
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();

        // Share months and banking services (no DB dependency)
        view()->share('months', [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ]);

        view()->share('list_of_banking_service', [
            'bank', 'transfer', 'rtgs', 'eftn', 'installment'
        ]);

        // Defer payment types sharing until runtime
        view()->composer('*', function ($view) {
            try {
                $payment_types = PaymentGateway::pluck('name')->toArray();
                $view->with('payment_types', $payment_types);
            } catch (\Exception $e) {
                $view->with('payment_types', []);
            }
        });

        // Share settings
        view()->composer('*', function ($view) {
            try {
                $setting = Setting::first();
                $view->with('setting', $setting);
            } catch (\Exception $e) {
                $view->with('setting', null);
            }
        });
    }
}
