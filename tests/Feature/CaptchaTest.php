<?php

namespace Tests\Feature;

use App\Rules\CaptchaValid;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class CaptchaTest extends TestCase
{
    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure we use file cache for persistence during the loops
        config(['cache.default' => 'file']);

        // Disable only your custom session security middleware
        // to allow Throttling to work.
        $this->withoutMiddleware([
            \App\Http\Middleware\CheckSessionValues::class,
        ]);
    }

    /** ---------------------------------------------------------
     * BASIC FUNCTIONALITY TESTS
     * --------------------------------------------------------- */

    public function test_captcha_generation_logic()
    {
        $response = $this->getJson(route('security.refresh'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'captcha_image_url',
                'captcha_audio',
                'captcha_token'
            ]);

        $this->assertEquals('127.0.0.1', session('captcha_ip'));
        $this->assertTrue(Session::has('captcha_hash'));
    }

    public function test_audio_stream_retrieval()
    {
        $refresh = $this->getJson(route('security.refresh'));
        $response = $this->get($refresh['captcha_audio']);

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'audio/wav');

        $this->assertNotEmpty($response->getContent());
    }

    /** ---------------------------------------------------------
     * SECURITY & RATE LIMITING TESTS
     * --------------------------------------------------------- */

    public function test_refresh_captcha_rate_limiting()
    {
        $testIp = '1.2.3.' . rand(1, 254);
        $limitReached = false;

        for ($i = 0; $i < 20; $i++) {
            $response = $this->withServerVariables(['REMOTE_ADDR' => $testIp])
                             ->getJson(route('security.refresh'));

            if ($response->status() === 429) {
                $limitReached = true;
                break;
            }
            $response->assertStatus(200);
        }

        $this->assertTrue($limitReached, "The rate limit (429) was never triggered.");
    }

    public function test_audio_request_rate_limiting()
    {
        $testIp = '4.5.6.' . rand(1, 254);
        $data = $this->withServerVariables(['REMOTE_ADDR' => $testIp])
                     ->getJson(route('security.refresh'))->json();

        $audioUrl = $data['captcha_audio'];
        $limitReached = false;

        for ($i = 0; $i < 20; $i++) {
            $resp = $this->withServerVariables(['REMOTE_ADDR' => $testIp])->get($audioUrl);
            if ($resp->status() === 429) {
                $limitReached = true;
                break;
            }
        }

        $this->assertTrue($limitReached, "The audio rate limit (429) was never triggered.");
    }

    public function test_audio_denied_on_ip_mismatch()
    {
        $refresh = $this->getJson(route('security.refresh'));
        $response = $this->withServerVariables(['REMOTE_ADDR' => '9.9.9.9'])
                         ->get($refresh['captcha_audio']);

        $response->assertStatus(403);
    }

    /** ---------------------------------------------------------
     * VALIDATION RULE TESTS (POSITIVE & NEGATIVE)
     * --------------------------------------------------------- */

    public function test_captcha_validation_rule_passes_with_valid_data()
    {
        $phrase = 'ABCDE';
        $ip = '127.0.0.1';
        session(['captcha_hash' => Hash::make($phrase), 'captcha_ip' => $ip]);

        $token = Crypt::encrypt([
            'key' => 'test-key',
            'ip'  => $ip,
            'created_at' => now()->subSeconds(10)->timestamp,
        ]);

        request()->merge(['captcha_token' => $token]);
        $rule = new CaptchaValid();
        $passed = true;
        $rule->validate('captcha', $phrase, function($message) use (&$passed) { $passed = false; });

        $this->assertTrue($passed);
    }

    public function test_captcha_fails_with_incorrect_phrase()
    {
        session(['captcha_hash' => Hash::make('CORRECT'), 'captcha_ip' => '127.0.0.1']);

        $rule = new CaptchaValid();
        $failed = false;
        $rule->validate('captcha', 'WRONG-INPUT', function($msg) use (&$failed) {
            $failed = true;
        });

        $this->assertTrue($failed, "Validation should fail for incorrect phrase.");
    }

    public function test_captcha_prevents_replay_attacks()
    {
        $phrase = 'REPLAY';
        $ip = '127.0.0.1';
        $token = Crypt::encrypt([
            'key' => 'replay-key', 'ip' => $ip, 'created_at' => now()->subSeconds(10)->timestamp,
        ]);

        session(['captcha_hash' => Hash::make($phrase), 'captcha_ip' => $ip]);
        request()->merge(['captcha_token' => $token]);

        $rule = new CaptchaValid();
        $rule->validate('captcha', $phrase, function($msg) {});

        $failed = false;
        $rule->validate('captcha', $phrase, function($msg) use (&$failed) {
            $failed = true;
            $this->assertEquals('This CAPTCHA has already been submitted.', $msg);
        });

        $this->assertTrue($failed);
    }

    public function test_captcha_fails_if_token_is_expired()
    {
        $phrase = 'ABCDE';
        $ip = '127.0.0.1';

        // Token created 2 hours ago
        $token = Crypt::encrypt([
            'key' => 'expired-key',
            'ip'  => $ip,
            'created_at' => now()->subHours(2)->timestamp,
        ]);

        session(['captcha_hash' => Hash::make($phrase), 'captcha_ip' => $ip]);
        request()->merge(['captcha_token' => $token]);

        $rule = new CaptchaValid();
        $failed = false;
        $rule->validate('captcha', $phrase, function($msg) use (&$failed) {
            $failed = true;
        });

        $this->assertTrue($failed, "Validation should fail for expired tokens.");
    }

    public function test_captcha_fails_if_token_is_invalid_garbage()
    {
        request()->merge(['captcha_token' => 'invalid-base64-garbage-string']);

        $rule = new CaptchaValid();
        $failed = false;
        $rule->validate('captcha', 'ABCDE', function($msg) use (&$failed) {
            $failed = true;
        });

        $this->assertTrue($failed, "Validation should fail for tampered/invalid tokens.");
    }

    public function test_captcha_provides_accessible_audio_alternative()
    {
        $response = $this->getJson(route('security.refresh'));

        // Ensure the audio URL is not just present, but a valid absolute URL
        $audioUrl = $response->json('captcha_audio');

        $this->assertStringStartsWith('http', $audioUrl, "Accessibility Error: Audio URL is missing or malformed.");

        // Verify the audio route is accessible via GET (Standard for screen reader players)
        $this->get($audioUrl)->assertStatus(200)->assertHeader('Content-Type', 'audio/wav');
    }

    /**
     * Test that the system provides the correct wait time after throttling.
     */
    public function test_rate_limit_provides_retry_after_header()
    {
        $testIp = '7.7.7.' . rand(1, 254);

        // 1. Exhaust the 5-request limit for 'captcha_refresh'
        for ($i = 0; $i < 5; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => $testIp])
                 ->getJson(route('security.refresh'));
        }

        // 2. The 6th request triggers the 429
        $response = $this->withServerVariables(['REMOTE_ADDR' => $testIp])
                         ->getJson(route('security.refresh'));

        $response->assertStatus(429);

        // 3. Verify the "Retry-After" header exists
        $this->assertTrue($response->headers->has('Retry-After'), "Response missing Retry-After header");

        // 4. Verify the wait time is logical (between 1 and 60 seconds)
        $waitSeconds = (int) $response->headers->get('Retry-After');
        $this->assertGreaterThan(0, $waitSeconds);
        $this->assertLessThanOrEqual(60, $waitSeconds);
    }
}