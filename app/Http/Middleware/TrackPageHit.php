<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PageHit;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class TrackPageHit
{
    public function handle(Request $request, Closure $next)
    {
        if (
            $request->isMethod('GET') &&
            $request->is('/') &&
            !$request->ajax() &&
            !$request->is('api/*') &&
            !$this->isBot($request)
        ) {
            $ip = $request->ip();
            $cooldownMinutes = 30; // ✅ Change this to your required time

            // Check last visit time for this IP
            $lastVisit = PageHit::where('ip_address', $ip)
                ->orderBy('created_at', 'desc')
                ->first();

            $shouldStore = false;

            if (!$lastVisit) {
                // First time visitor
                $shouldStore = true;
            } else {
                // Check if cooldown time passed
                $minutesPassed = now()->diffInMinutes($lastVisit->created_at);

                if ($minutesPassed >= $cooldownMinutes) {
                    $shouldStore = true;
                }
            }

            if ($shouldStore) {
                PageHit::create([
                    'ip_address' => $ip,
                    'visit_date' => now()->toDateString(),
                    'created_at' => now(),
                ]);

                // Clear cache so values refresh
                Cache::forget('total_hits');
                Cache::forget('today_hits');
                Cache::forget('today_unique');
            }
        }

        // Cached counters
        $totalHits = Cache::remember('total_hits', 60, function () {
            return PageHit::count();
        });

        $todayHits = Cache::remember('today_hits', 60, function () {
            return PageHit::whereDate('visit_date', now()->toDateString())
                ->count();
        });

        $todayUnique = Cache::remember('today_unique', 60, function () {
            return PageHit::whereDate('visit_date', now()->toDateString())
                ->distinct('ip_address')
                ->count('ip_address');
        });

        View::share('totalHits', 35000+$totalHits);
        View::share('todayHits', $todayHits);
        View::share('todayUnique', $todayUnique);

        return $next($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Bot Detection
    |--------------------------------------------------------------------------
    */
    private function isBot(Request $request)
    {
        $userAgent = strtolower($request->userAgent());

        $bots = [
            'googlebot',
            'bingbot',
            'yahoo',
            'baiduspider',
            'yandex',
            'duckduckbot',
            'crawler',
            'spider',
            'bot'
        ];

        foreach ($bots as $bot) {
            if ($userAgent && str_contains($userAgent, $bot)) {
                return true;
            }
        }

        return false;
    }
}
