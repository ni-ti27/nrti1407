<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class CaptchaValid implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $token = request()->input('captcha_token');
        $currentIp = request()->ip();

        if (!$token) {
            $fail('Missing CAPTCHA token.');
            return;
        }

        // 1. Anti-Replay: Prevent using the same token twice
        $cacheKey = 'captcha_used_' . md5($token);
        if (Cache::has($cacheKey)) {
            $fail('This CAPTCHA has already been submitted.');
            return;
        }

        try {
            $data = Crypt::decrypt($token);
        } catch (\Exception $e) {
            $fail('Invalid CAPTCHA security token.');
            return;
        }

        // 2. IP Binding Check: Token IP must match Current IP
        if (!isset($data['ip']) || $data['ip'] !== $currentIp) {
            $fail('Security violation: IP address mismatch.');
            return;
        }

        // 3. Session IP Check: Session must belong to the same IP
        if (session('captcha_ip') !== $currentIp) {
            $fail('Security violation: Session IP mismatch.');
            return;
        }

        $elapsed = now()->timestamp - ($data['created_at'] ?? 0);

        // 4. Anti-Bot: Speed check (Real humans usually take > 3 seconds)
        if ($elapsed < 3) {
            $fail('Submission received too quickly. Please try again.');
            return;
        }

        // 5. Expiry: 5-minute window
        if ($elapsed > 300) {
            $fail('CAPTCHA session expired. Please refresh.');
            return;
        }

        // 6. Phrase Verification
        $storedHash = session('captcha_hash');
        if (!$storedHash || !Hash::check(strtoupper($value), $storedHash)) {
            $fail('Incorrect CAPTCHA code.');
            return;
        }

        // Success: Mark token as used and clean up session
        Cache::put($cacheKey, true, now()->addMinutes(10));
        session()->forget(['captcha_hash', 'captcha_audio_text', 'captcha_ip']);
    }
}