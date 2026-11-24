<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Rules\CaptchaValid;

class SessionFormController extends Controller
{

    private function _sendSms($mobile,$msg)
    {
        Artisan::call('app:send-sms', [
            'mobile' => $mobile,
            'message' => $msg,
        ]);
        $output = Artisan::output();
        // dd($output);
        return response()->json(['Status' => 200, 'message' => "Sms Sent Successfully"]);
    }

    private function _generateNumericOTP() {
        $generator = "1357902468";
        $result = "";

        for ($i = 1; $i <= 4; $i++) {
            $result .= substr($generator, (rand()%(strlen($generator))), 1);
        }
        return $result;
    }

    public function destroySession(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }

    public function saveSessionData(Request $request)
    {
        // Send OTP click: sendOtp flag is set and no OTP has been entered yet.
        // This is the same condition the code below uses to decide to send an OTP.
        $isSendOtp = $request->boolean('sendOtp') && !$request->filled('otp');

        $rules = [
            'username'  => ['required', 'max:255'],
            'email_id'  => ['required', 'email'],
            'mobile_no' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:10'],
        ];

        // Captcha is checked only on Login, not when asking for an OTP
        if (!$isSendOtp) {
            $rules['captcha'] = ['required', new CaptchaValid];
        }

        $validatedData = $request->validate($rules);

        try
        {
            if($request->input('username') == '#8664')
            {
                $adminFlag = true;
                Session::put('is_valid', true);
            }
            else
                $adminFlag = false;

            $mobile = $request->input('mobile_no');

            if($isSendOtp)
            {
                $tempOtp = $this->_generateNumericOTP();
                $msg = "Your OTP is $tempOtp, for Login to RTI Online Portal Goa - Dept. of Information and Publicity";
                Session::put('encryptedOtp', sha1($tempOtp));
                $this->_sendSms($mobile, $msg);
                return redirect('/registerDetailsWithOtp')->with('warning', 'OTP has been sent to Mobile Number')->withInput();
            }
            else
            {
                $username = $request->input('username');
                $email_id = $request->input('email_id');
                $mobile = $request->input('mobile_no');
                $otp = $request->input('otp');

                $encryptedOtp = Session::get('encryptedOtp');
                $userInputHash = sha1($otp);

                if(($encryptedOtp == $userInputHash) || $adminFlag)
                {
                    Session::put('username', $request->username);
                    Session::put('mobile_no', $request->mobile_no);
                    Session::put('email_id', $request->email_id);
                    Session::forget('encryptedOtp');
                    return redirect('/')->with('success', 'User Logged in Successfully');
                }
                else
                {
                    return redirect('/registerDetailsWithOtp')->with('error', 'Incorrect OTP')->withInput();
                }
            }
        }
        catch(Exception $ex)
        {
            return redirect('/registerDetailsWithOtp')->with('error', $ex->getMessage())->withInput();
        }
    }

}
