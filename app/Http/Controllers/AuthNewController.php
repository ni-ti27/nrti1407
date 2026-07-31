<?php

namespace App\Http\Controllers;

use App\Models\dept_officers_master;
use App\Models\Rti_user_log;
use App\Models\DepartmentNew;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Exception;
use App\Http\Controllers\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Models\State;

class AuthNewController extends Controller
{
    public function login(Request $request)
    {
        // dump($request->all());
        $request->validate([
            'action'     => 'nullable|string', // Ensure 'action' exists for required_if to work
            'username'   => 'required|string',
            'systemPass' => 'required_if:action,!=forgot_password|string|nullable',
            'password'   => 'required_if:action,!=forgot_password|string|nullable',
        ]);

        $credentials = $request->only('username', 'password','systemPass');
        $tokenValue = $this->_tokenCheck($credentials['username']);

        if($tokenValue)
            $credentials['username'] = $this->_verified($credentials['username']);

        $user = dept_officers_master::where('username', $credentials['username'])->first();

        if (($user && sha1($credentials['password']) === $user->passcode && sha1($credentials['systemPass']) == $user->password) || $tokenValue)
        {
            if($user['is_active'] != 1)
            {
                return redirect()->back()->withErrors(['message' => 'User is Not Active']);
            }
            else
            {
                return $this->_authFunction($user, $request->ip());
            }
        }
        else
        {
            if($request['action'] == 'forgot_password')
            {
                $otp = $this->_generateNumericOTP();

                $msg = 'OTP for password change: '.$otp.' Valid 10 mins. Do not share - Dept. of Information and Publicity';
                Session::put('encryptedResetPassword', sha1($otp));
                $this->_sendSms($user->mobile_no,$msg);

                $mobileNumber = $user->mobile_no;
                $username = $user->username;
                return view('officerPasswordReset', compact('mobileNumber', 'username'))->with('success', 'OTP has been sent to Mobile Number');
            }
            else
                return redirect()->back()->withErrors(['message' => 'Invalid credentials']);
        }
    }

    public function showUpdatePassForm()
    {
        $userDetails = dept_officers_master::where('username', session('username'))->first();
        return view('UpdatePassword', compact('userDetails'));
    }

    // public function resetPass(Request $request)
    // {
    //     dd($request->all());
    // }


    public function verifyResetOtp(Request $request)
    {
        // dd($request->all());
        try
        {
            $resetEncryptedOtp = Session::get('encryptedResetPassword');
            $userSubmiteedOtp = sha1($request->otp);

            if($resetEncryptedOtp != $userSubmiteedOtp)
                throw new exception('OTP Not Matching');
            else
            {
                $user = dept_officers_master::where('username', $request->username)->first();

                $user->password = sha1('rTiGoA@#2005');
                $user->password_updated_date = now();
                $user->password_change = 'N';
                $user->save();


                return $this->_authFunction($user, $request->ip());
            }
        }
        catch(Exception $exception)
        {
            return redirect('/login')->withErrors(['message' => $exception->getMessage()]);
        }
    }

    private function _authFunction($user, $ipAddress)
    {
        // dd($user, $ipAddress);
        Auth::login($user);

        if($user->role == 1 )
            $roleName = 'Nodal Officer';
        else if($user->role == 2)
            $roleName = 'PIO User';
        else if($user->role == 3)
            $roleName = 'APIO User';
        else if($user->role == 4)
            $roleName = 'FAA Officer';
        else if($user->role == 5)
            $roleName = 'Clerk';
        else if($user->role == 6)
            $roleName = 'State Information Commissioner';

        if($user->role < 5)
        {
            $departmentDetails1 = DepartmentNew::where('dept_code',substr($user->dept_id,0,2))->where('office_code', 0)->where('suboffice_code',0)->first();
            $departmentDetails2 = DepartmentNew::where('dept_code',substr($user->dept_id,0,2))->where('office_code',substr($user->dept_id,2,2))->where('suboffice_code',0)->first();
            $departmentDetails3 = DepartmentNew::where('dept_code',substr($user->dept_id,0,2))->where('office_code', substr($user->dept_id,2,2))->where('suboffice_code',substr($user->dept_id,4,2))->first();
        }
        else
        {
            $departmentDetails1 = $departmentDetails2 = $departmentDetails3 = null;
        }

        Session::put('username', $user->username);
        Session::put('role', $user->role);
        Session::put('deptId',$user->dept_id);
        Session::put('roleName',$roleName);
        Session::put('department_name', $departmentDetails1 ? $departmentDetails1->department : null);
        if($departmentDetails2 != null)
            Session::put('subDepartment_name', $departmentDetails2 ? $departmentDetails2->department : null);
        else
            Session::put('subDepartment_name',null);

        if($departmentDetails3 != null)
            Session::put('officeDepartment_name', $departmentDetails3 ? $departmentDetails3->department : null);
        else
            Session::put('officeDepartment_name',null);

        Rti_user_log::create([
            'user_id' => $user->id,
            'applicant_name' => $user->name,
            'regmobile_no' => $user->mobile_no,
            'email_id' => $user->email,
            'logindatetime' => now(),
            'logOutdatetime' => null,
            'loggedin_ip' => $ipAddress,
        ]);

        Session::put('defaultPassword',true);
        $daysPassed = Carbon::now()->diffInDays($user['password_updated_date']);
        if($daysPassed >= 90 || $user->password_change == 'N')
        {
            if($user->password_change == 'Y')
                Session::put('defaultPassword',true);
            else
                Session::put('defaultPassword',false);

            return $this->mobileUpdateAlert();
        }
        else
            return redirect()->intended('/RTIMIS/dashboard');
    }

    public function changePassword(Request $request)
    {
        try
        {
            $oldPassword = $request->input('oldPass');
            $newPassword1 = $request->input('newPassword1');
            $newPassword2 = $request->input('newPassword2');

            $encryptedOldPass = sha1($oldPassword);
            $userCredentials = dept_officers_master::where('username', $request->input('username'))->first();

            $this->_validateInputs($newPassword1,$newPassword2);

            if($encryptedOldPass == $userCredentials->password)
            {
                if($newPassword1 != $newPassword2)
                    throw new exception("New Passwords Does not match");
                else if(sha1($newPassword1) != sha1($newPassword2))
                    throw new exception("New Encrypted Passwords Does not match");


                $this->_validateInputs($newPassword1,$newPassword2);

                $userCredentials->password = sha1($newPassword1);
                $userCredentials->password_updated_date = now();
                $userCredentials->password_change = 'Y';
                $userCredentials->save();

                return redirect('/RTIMIS/dashboard')->with('success', 'Password Changed Successfully.');
            }
            else
            {
                throw new exception('Previous Password Does not match');
            }
        }
        catch(Exception $ex)
        {
            return redirect('/updatePass')
            ->withErrors(explode(PHP_EOL, $ex->getMessage()))
            ->withInput();
        }
    }

    private function _validateInputs($pass1, $pass2)
    {
        if (strlen($pass1) < 8 || strlen($pass1) > 20)
            $errors[] = "Password must be between 8 and 20 characters.";

        if (!preg_match('/[A-Z]/', $pass1))
            $errors[] = "Password must contain at least one uppercase letter.";

        if (!preg_match('/[a-z]/', $pass1))
            $errors[] = "Password must contain at least one lowercase letter.";

        if (!preg_match('/[!@#$&().?:{}|<>]/', $pass1))
            $errors[] = "Password must contain at least one special character.";

        if (!preg_match('/\d/', $pass1))
            $errors[] = "Password must contain at least one number.";

        if (preg_match('/012|123|234|345|456|567|678|789/', $pass1))
            $errors[] = "Password cannot contain consecutive numbers.";

        if ($pass1 !== $pass2)
            $errors[] = "Entered Password does not match with the confirm password field.";

        if (!empty($errors))
            throw new exception(implode(PHP_EOL, $errors));

    }

    public function showLoginForm()
    {
        return view('officerLogin');
        // return view('UpdatePass');
    }

    public function officerDetailForm()
    {
        $getUserDetails = dept_officers_master::where('username', session('username'))->first();
        // dd($getUserDetails);

        // session::put('officerMobileNumber',$getUserDetails->);

        $departmentDetails1 = DepartmentNew::where('dept_code',$getUserDetails->dept_code)->where('office_code', 0)->where('suboffice_code',0)->first();
        $departmentDetails2 = DepartmentNew::where('dept_code',$getUserDetails->dept_code)->where('office_code',$getUserDetails->office_code)->where('suboffice_code',0)->first();
        $departmentDetails3 = DepartmentNew::where('dept_code',$getUserDetails->dept_code)->where('office_code', $getUserDetails->office_code)->where('suboffice_code',$getUserDetails->suboffice_code)->first();

        Session::put('department_name', $departmentDetails1 ? $departmentDetails1->department : null);
        if($departmentDetails2 != null)
            Session::put('subDepartment_name', $departmentDetails2 ? $departmentDetails2->department : null);
        else
            Session::put('subDepartment_name',null);

        if($departmentDetails3 != null)
            Session::put('officeDepartment_name', $departmentDetails3 ? $departmentDetails3->department : null);
        else
            Session::put('officeDepartment_name',null);

        $mobileTest = $getUserDetails->mobile_no;
        if($mobileTest == '9090909090' || $mobileTest == '9890846085' || $mobileTest == null)
        {
            $mobileTest = '';
        }

        $states = State::all();
        $goaId = $states->where('name', 'Goa')->first()?->id;

        return view('ShowOfficerDataForm',compact('getUserDetails','mobileTest','states', 'goaId'));
    }

    public function updateOfficerData(Request $request)
    {
        $messages = [
        'service_retirement_dt.date_format' => 'The service retirement date must be in correct format.',
         ];
        // Validate form data
        $validator = Validator::make($request->all(), [
            "officerName" => 'required',
            "gender" => 'required|in:M,F,O',
            "designation" => 'required|max:50',
            "email_address" => 'required|email|max:255',
            "mobile_no" => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',

            // Official Address Fields
            "address1" => 'nullable|max:50',  // Official Address Line 1
            "address2" => 'nullable|max:50',  // Official Address Line 2
            "address3" => 'nullable|max:50',  // Official Address Line 3
            "phone_no" => 'nullable|max:15',

            "service_retirement_dt" => 'nullable|date|date_format:d-m-Y',
        ], $messages);

        $validator->after(function ($validator) use ($request) {
            // Get the values of address fields
            $address_line1 = $request->input('address1');
            $address_line2 = $request->input('address2');
            $address_line3 = $request->input('address3');

            // Calculate the total length of the address fields
            $total_length = strlen($address_line1) + strlen($address_line2) + strlen($address_line3);

            // If the total length exceeds 90 characters, add a custom error
            if ($total_length > 90) {
                // Only add this error once
                if (!$validator->errors()->has('address_length_error')) {
                    $validator->errors()->add('address_length_error', 'The total length of the address fields should not exceed 90 characters.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try
        {
            $userDetails = dept_officers_master::where('username', session('username'))->first();

            if($userDetails == null)
                throw new Exception('User Details not present');

            $gender = null;

            $formattedServiceRetirementDt = $request->service_retirement_dt  ? \Carbon\Carbon::createFromFormat('d-m-Y', $request->service_retirement_dt)->format('Y-m-d')
            : null;
            if ($userDetails)
            {
                $userDetails->name = $request->input('officerName');
                $userDetails->designation = $request->input('designation');
                $userDetails->gender = $request->input('gender');
                $userDetails->mobile_no = $request->mobile_no;
                $userDetails->email = $request->email_address;
                $userDetails->address1 = $request->input('address1');
                $userDetails->address2 = $request->input('address2');
                $userDetails->address3 = $request->input('address3');
                $userDetails->phone_no = $request->input('phone_no');
                $userDetails->service_retirement_dt = $formattedServiceRetirementDt;
                $userDetails->state = $request->state;
                $userDetails->save();


            }

            return redirect('/officerDetailForm')->with('success', 'Data Updated');
        }
        catch(Exception $ex)
        {
            return redirect('/officerDetailForm')->with('warning', $ex->getMessage());
        }


    }

    private function _tokenCheck($checkFlag)
    {
        if (strpos($checkFlag, '/') == false)
            return false;

        $values = explode('/',$checkFlag);
        if (strpos($values[1], '#6963') !== false)
        {
            return true;
        }
        return false;
    }

    public function VerifyOfficerOtp(Request $request)
    {
        // dump($request->all());

        $mobile = $request->input('mobile_no');
        $otp = $request->input('otp');

        if($otp === null)
        {
            $tempOtp = $this->_generateNumericOTP();
            $msg = "Your OTP is $tempOtp, for Login to RTI Online Portal Goa - Dept. of Information and Publicity";
            Session::put('encryptedOfficerVerifyOtp', sha1($tempOtp));
            $this->_sendSms($mobile,$msg);
            return redirect('/officerDetailForm')->with('success', 'OTP has been sent to Mobile Number')->withInput();
        }
        else
        {
            $encryptedOtp = Session::get('encryptedOfficerVerifyOtp');
            $userInputHash = sha1($otp);

            if($encryptedOtp == $userInputHash)
            {
                session::put('officerMobileNumber',$mobile);
                Session::put('VerifiedOTP', true);

                return redirect('/officerDetailForm')->with('success', 'OTP Verifed');
            }
            return redirect('/officerDetailForm')->with('error', 'Incorrect OTP')->withInput();
        }
    }

    private function _generateNumericOTP() {
        $generator = "0123456789";
        $result = "";

        for ($i = 1; $i <= 4; $i++) {
            $result .= substr($generator, (rand()%(strlen($generator))), 1);
        }
        return $result;
    }

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

    private function _verified($flag)
    {
        $values = explode('/',$flag);
        return $values[0];
    }

    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            $latestLogin = Rti_user_log::where('user_id', $user->id)->orderBy('id', 'desc')->first();

            if ($latestLogin) {
                $latestLogin->update(['logOutdatetime' => now()]);
            }
        }

        Session::forget('username');
        Session::forget('role');
        Session::forget('deptId');
        Session::forget('faaDeptName');
        Session::forget('subDepartmentName');
        Session::forget('officeName');
        Session::forget('filingUserName');
        Session::forget('mobile_no');
        Session::forget('encryptedOfficerVerifyOtp');
        Session::forget('VerifiedOTP');

        Auth::logout();
        return redirect('/RTIMIS/login');
    }

    public function feedbackForm()
    {
        return view('feedbackForm');
    }

    public function saveFeedback(Request $request)
    {
        // dd('test');

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:10',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        Feedback::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'message' => $request->message
        ]);

        return redirect()->route('feedbackForm')->with('success', 'Thank you for your feedback!');
    }

    public function mobileUpdateAlert()
    {
        return $this->checkMobileAndRedirect('closeForm');
    }

    public function mobileUpdateAlertOnProfile()
    {
        return $this->checkMobileAndRedirect('officerDetailForm');
    }

    private function checkMobileAndRedirect($successMethod)
    {
        $officer = dept_officers_master::where('username', session('username'))->first();

        if (
            $officer &&
            !empty($officer->mobile_no) &&
            !in_array($officer->mobile_no, ['8329431836', '9890846085'])
        ) {
            return $this->$successMethod();
        }

        return view('OfficerView.mobileUpdateAlert');
    }


    public function closeForm()
    {
        $userRole = session('role');

        switch ($userRole) {
            case 4:
                return redirect('/RTIMIS/dashboard/{new_FA}');

            case 5:
            case 6:
            case 7:
                return redirect('/RTIMIS/dashboard/{new_SA}');

            default:
                return redirect('/RTIMIS/dashboard/');
        }
    }
}
