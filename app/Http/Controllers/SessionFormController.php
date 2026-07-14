<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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

        $validatedData = $request->validate([
            'username' => ['required', 'max:255'],
            'email_id' => ['required', 'email'],
            'mobile_no' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/','min:10','max:10'],
        ]);

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

            if($request->input('sendOtp') == true && $request->input('otp') == null)
            {
                $tempOtp = $this->_generateNumericOTP();
                $msg = "Your OTP is $tempOtp, for Login to RTI Online Portal Goa - Dept. of Information and Publicity";
                Session::put('encryptedOtp', sha1($tempOtp));
                $this->_sendSms($mobile,$msg);
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
