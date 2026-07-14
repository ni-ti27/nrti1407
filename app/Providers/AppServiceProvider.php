<?php

namespace App\Providers;

use Illuminate\Http\Request; // Add this line
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            // Limit CAPTCHA refreshes to 5 per minute per IP
        RateLimiter::for('captcha_refresh', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Limit Audio generation to 10 per minute (slightly higher for accessibility)
        RateLimiter::for('captcha_audio', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // This logic only runs when 'UsersView.header' (or your header path) is loaded
        View::composer('UsersView.header', function ($view) {
            $hasPending = false;

            if (session()->has('mobile_no')) {

                $mobileNo = session('mobile_no');

                // Use the same query logic you provided to check existence
                $hasPending = DB::table('submit_requests as sr')
                    ->leftJoin('payment__details as pd', function ($join) {
                        $join->on('pd.applicant_id', '=', 'sr.id')
                             ->whereRaw('pd.id = (select max(id) from payment__details where applicant_id = sr.id)');
                    })
                    ->where('sr.mobile_no', $mobileNo)
                    ->where('sr.is_bpl', 0)
                    ->whereNull('pd.id')
                    ->whereIn('sr.payment_details', ['N/A'])
                    ->where('sr.created_at', '>=', Carbon::now()->subDays(7)->startOfDay())
                    ->exists();
            }

            $view->with('hasPendingRecords', $hasPending);
        });
    }

}
