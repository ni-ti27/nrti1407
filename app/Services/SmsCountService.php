<?php

namespace App\Services;

use App\Models\SmsCount;
use Illuminate\Support\Facades\DB;

class SmsCountService
{
    public function increment()
    {
        DB::transaction(function () {
            // Only one row exists; create if missing
            $smsCount = SmsCount::firstOrCreate(
                [],
                ['count' => 0, 'start_date' => now(), 'last_update_date' => now()]
            );

            // If count is 0, reset start_date
            if ($smsCount->count === 0) {
                $smsCount->start_date = now();
            }
            // Increment count
            $smsCount->increment('count');

            // Update last_update_date
            $smsCount->last_update_date = now();
            $smsCount->save();
        });
    }
}