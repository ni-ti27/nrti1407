<?php

namespace App\Http\Controllers;

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CaptchaController extends Controller
{
    public function refresh(Request $request)
    {
        if (!app()->runningUnitTests()) {
            if (ob_get_level()) ob_end_clean();
        }

        $phrase = strtoupper(Str::random(5));
        $builder = new CaptchaBuilder($phrase);
        $builder->build(160, 46);

        $keyIdentifier = Str::random(12);

        $tokenData = [
            'key' => $keyIdentifier,
            'ip'  => $request->ip(),
            'created_at' => now()->timestamp,
        ];

        $token = Crypt::encrypt($tokenData);

        // Clear old keys
        session()->forget('captcha_audio_text');

        session([
            'captcha_hash'  => Hash::make($phrase),
            'captcha_ip'    => $request->ip(),
            // ENCRYPT the phrase before putting it in the session
            'captcha_audio_text.' . $keyIdentifier => Crypt::encryptString($phrase),
        ]);

        session()->save();

        return response()->json([
            'captcha_image_url' => $builder->inline(),
            'captcha_audio'     => route('security.audio', ['key' => $keyIdentifier, 't' => $token]),
            'captcha_token'     => $token
        ]);
    }

    public function audio(Request $request, $key)
    {
        try {
            $tokenData = Crypt::decrypt($request->query('t'));

            if ($tokenData['ip'] !== $request->ip() || $tokenData['key'] !== $key) {
                return response('Unauthorized: IP Mismatch', 403);
            }

            // Updated to 120 seconds (2 minutes) to match frontend timer
            if (now()->timestamp - $tokenData['created_at'] > 120) {
                return response('Expired', 403);
            }
        } catch (\Exception $e) {
            return response('Invalid Token', 403);
        }

        $encryptedText = session()->get('captcha_audio_text.' . $key);
        if (!$encryptedText) {
            return response('Captcha Expired', 403);
        }

        try {
            // DECRYPT the phrase for eSpeak to use
            $text = Crypt::decryptString($encryptedText);
        } catch (\Exception $e) {
            return response('Data Corruption', 403);
        }

        $spacedText = implode(', ', str_split($text));

        $espeakPath = Cache::rememberForever('espeak_binary_path', function () {
            $path = exec('which espeak');
            return $path ?: '/usr/bin/espeak';
        });

        if (!file_exists($espeakPath) || !is_executable($espeakPath)) {
            return response()->json([
                'error' => 'Audio engine unavailable',
                'message' => 'Please use the visual challenge.'
            ], 503);
        }

        $command = "HOME=/tmp $espeakPath -a 200 -s 130 " . escapeshellarg($spacedText) . " --stdout";
        $audioData = shell_exec($command);

        return response($audioData)
            ->header('Content-Type', 'audio/wav')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}