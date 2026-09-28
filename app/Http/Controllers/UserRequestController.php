<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\District;
use App\Models\State;
use Illuminate\Support\Facades\DB;
use App\Models\Taluka;
use App\Models\Village;
use App\Models\DepartmentNew;
use App\Models\SubmitRequest;
use App\Models\Profile;
use App\Models\application_history;
use App\Models\action_details;
use App\Models\dept_officers_master;
use App\Models\Rti_Dept_Id;
use App\Models\RtiApplicantRegdata;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use App\Rules\CaptchaValid;
use Illuminate\Support\Facades\Validator;
use App\Models\online_payment_details;

class UserRequestController extends Controller
{
    public function preForm()
    {
        $stateData = State::where('id', 6)->get();
        $profileData = Profile::where('mobile_no', session('mobile_no'))->first();
        $deptData = DepartmentNew::where(['office_code' =>0, 'suboffice_code' => 0, 'office' => 'NA', 'suboffice' => 'NA'])->get();
        // dump(json_encode($stateData));
        $profileExists = Profile::where('mobile_no',  session('mobile_no'))->exists();

        $show_skip = 0;
        if($profileExists)
        {
            $show_skip = 1;
        }
        return view('Profile',compact('stateData','deptData', 'profileData', 'show_skip'));
    }

    public function storePreForm(Request $request)
    {
        if ($request->has('skip')) {
            return redirect()->route('submitreqform');
        }

        $request->merge([
            'country' => 1,
            'origin_country' => 1,
            'phone_no' => $request->phone_no ?? 0,
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'gender' => 'required',
            'address_line1' => 'required|max:50',
            'address_line2' => 'required|max:50',
            'address_line3' => 'required|max:50',
            'pincode' => 'required|numeric|digits:6',
            'country' => 'required',
            'state' => 'required',
            'district' => 'required',
            'taluka' => 'required',
            'village' => 'required',
            'mobile_no' => [
                'required',
                'digits:10',
                Rule::unique('profiles', 'mobile_no')->ignore($request->mobile_no, 'mobile_no'),
            ],
            'email_id' => 'required|email',
            'edu_status' => 'required',
            'qualification' => 'required_if:edu_status,educated',
            'origin_country' => 'required',
        ]);
        $validator->after(function ($validator) use ($request) {
            // Get the values of address fields
            $address_line1 = $request->input('address_line1');
            $address_line2 = $request->input('address_line2');
            $address_line3 = $request->input('address_line3');

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


        // Check if the validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $formData = $request->all();

        $formData['edu_status'] = $request->edu_status === 'educated' ? 1 : 0;
        $formData['qualification'] = $formData['edu_status'] ? ($request->qualification ?? 0) : 0;

        if (empty($formData['phone_no'])) {
            $formData['phone_no'] = 0000000;
        }


        $profile = new Profile();
        $fillableFields = $profile->getFillable();
        $dataToSave = array_intersect_key($formData, array_flip($fillableFields));

        Profile::updateOrCreate(
             ['mobile_no' => $request->mobile_no],
            $dataToSave
        );

        $newName = $request->name;


        $sessionUsername = session('username');

        if ($sessionUsername !== $newName) {
            session(['username' => $newName]);
        }


        return redirect()
            ->route('submitreqform')
            ->with('success', 'Profile saved successfully');
    }

    public function pendingList(Request $request)
    {
        $mobileNo = session('mobile_no');
        // Single query execution to fetch data
        // If no records are found, $nrStatus will be empty, allowing us to redirect
        $nrStatus = DB::table('submit_requests as sr')
            ->leftJoin('payment__details as pd', function ($join) {
                $join->on('pd.applicant_id', '=', 'sr.id')
                    ->whereRaw('pd.id = (select max(id) from payment__details where applicant_id = sr.id)');
            })
            ->select([
                'sr.*',
                'sr.reg_no as registration_no',
                'pd.id as transaction_id',
                'pd.echallan_no',
                'pd.total_amt'
            ])
            ->where('sr.mobile_no', $mobileNo)
            ->where('sr.is_bpl', 0)
            ->where('sr.payment_details', 'N/A')
            ->where('sr.created_at', '>=', now()->subDays(7)->startOfDay())
            ->whereNull('pd.id') // Filters for records without payment details
            ->orderBy('sr.created_at', 'desc')
            ->paginate(10);

        // Redirect if no pending requests exist
        if ($nrStatus->isEmpty()) {
            return redirect()->route('profile');
        }

        return view('Applicant.PendingList', [
            'nrStatus' => $nrStatus,
            'divSelector' => $request->input('divSelector', 'display1')
        ]);
    }

    /*public function pendingList(Request $request)
    {
        $mobileNo = session('mobile_no');

        if ($mobileNo) {
            $hasPending = DB::table('submit_requests as sr')
            ->where('sr.mobile_no', $mobileNo)
            ->where('sr.is_bpl', 0)
            ->where('sr.payment_details', 'N/A')
            ->where('sr.created_at', '>=', Carbon::now()->subDays(7)->startOfDay())
            ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
            ->from('payment__details as pd')
            ->whereColumn('pd.applicant_id', 'sr.id');
            })
            ->exists();
        }

        if (!$hasPending) {
            return redirect()->route('profile');
        }
        $divSelector = $request->input('divSelector', 'display1');

       $nrStatus = DB::table('submit_requests as sr')

            ->leftJoin('payment__details as pd', function ($join) {
                $join->on('pd.applicant_id', '=', 'sr.id')
                    ->whereRaw('pd.id = (select max(id) from payment__details where applicant_id = sr.id)');
            })

            ->select(
                'sr.*',
                'sr.reg_no as registration_no',
                'pd.id as transaction_id',
                'pd.echallan_no',
                'pd.total_amt',
                DB::raw('NULL as online_pay_status') // Explicitly set to NULL since no payment exists
            )
            ->where('sr.mobile_no', session('mobile_no'))
            ->where('sr.is_bpl', 0)
            ->whereNull('pd.id')

            ->whereIn('sr.payment_details', ['N/A'])
            ->where('sr.created_at', '>=', Carbon::now()->subDays(7)->startOfDay())
            ->orderBy('sr.created_at', 'desc')
            ->paginate(10);

        return view('Applicant.PendingList', compact('nrStatus', 'divSelector'));
    }*/

   /* public function update($id)
    {
        $submitRequest = SubmitRequest::with('state', 'village', 'district', 'actionDetails')->find($id);

        $departmentDetails1 = DepartmentNew::where('dept_code',$submitRequest->applied_dept)->where('office_code', 0)->where('suboffice_code',0)->first();
        $departmentDetails2 = DepartmentNew::where('dept_code',$submitRequest->applied_dept)->where('office_code', $submitRequest->applied_subdept)->where('suboffice_code',0)->first();
        $departmentDetails3 = DepartmentNew::where('dept_code',$submitRequest->applied_dept)->where('office_code', $submitRequest->applied_subdept)->where('suboffice_code',$submitRequest->applied_office)->first();

        $filePath  = $submitRequest['document_path'];
        $filePath = Storage::url($filePath);

        return view('Applicant.Edit', [
            'deptDetails' => $departmentDetails1->department,
            'officeDetails' => $departmentDetails2->department,
            'subOfficeDetails' => $departmentDetails3->department,
            'submitRequest' => $submitRequest,
            'filePath' => $filePath,
            'submitReqId' => $id
        ]);
    }*/

    public function update($id)
    {
        // 1. Fetch the primary submission request data
        $requestData = SubmitRequest::with(['state', 'village', 'district', 'actionDetails'])->findOrFail($id);

        // 2. Fetch Master Data lists to populate drop-down menus
        $deptData = DepartmentNew::where('office_code', 0)
            ->where('suboffice_code', 0)
            ->get();
        $stateData = State::all();

        // 3. Fetch dependent dropdowns using existing class methods

        // Dependent department dropdowns
        $subDeptDataResponse = $this->getSubDept($requestData->applied_dept);
        $subDeptData = $subDeptDataResponse->getData();

        $officeDataResponse = $this->getOffice($requestData->applied_dept, $requestData->applied_subdept);
        $officeData = $officeDataResponse->getData();

        // Dependent regional dropdowns
        $districtDataResponse = $this->getDistrict($requestData->state);
        $districtData = $districtDataResponse->getData();

        $talukaDataResponse = $this->getTaluka($requestData->district);
        $talukaData = $talukaDataResponse->getData();

        $villageDataResponse = $this->getVillage($requestData->taluka);
        $villageData = $villageDataResponse->getData();

        // 4. Document File Resolution
        $filePath = $requestData->document_path ? Storage::url($requestData->document_path) : null;

        // 5. Return to the 'Applicant.Edit' view
        return view('Applicant.Edit', compact(
            'requestData',
            'deptData',
            'subDeptData',
            'officeData',
            'stateData',
            'districtData',
            'talukaData',
            'villageData',
            'filePath'
        ));
    }

    public function editFormSubmit(Request $request, $id)
    {
        // 1. Honeypot Check
        if ($request->filled('middle_name')) {
            \Log::info("Bot caught by honeypot: " . $request->ip());
            return back()->with('success', 'Form submitted successfully!');
        }
        $request['country'] = 1;
        $request['origin_country'] = 1;
        // 2. Validation
        $validator = Validator::make($request->all(), [
            'applied_dept' => 'required',
            'applied_subdept' => 'required',
            'applied_office' => 'required',
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'gender' => 'required',
            'address_line1' => 'required|max:50',
            'address_line2' => 'required|max:50',
            'address_line3' => 'required|max:50',
            'pincode' => 'required|numeric|digits:6',
            'country' => 'required',
            'state' => 'required',
            'district' => 'required',
            'taluka' => 'required',
            'village' => 'required',
            'mobile_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'email_id' => 'required|email',
            'edu_status' => 'required',
            'qualification' => 'required_if:edu_status,educated',
            'origin_country' => 'required',
            'is_bpl' => 'required',
            'request_text' => 'string|max:3000',
            'document' => 'nullable|file|max:5120|mimetypes:application/pdf',
            'captcha'   => ['required', new CaptchaValid],
        ]);

        $validator->after(function ($validator) use ($request) {
            $total_length = strlen($request->address_line1 ?? '')
                        + strlen($request->address_line2 ?? '')
                        + strlen($request->address_line3 ?? '');
            if ($total_length > 90) {
                $validator->errors()->add('address_length_error', 'The total length of the address fields should not exceed 90 characters.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $formData = $request->all();

        // 3. Fetch existing record
        $submitRequestObj = SubmitRequest::findOrFail($id);
        $actionChange = null;

        $deptCode = $submitRequestObj->createDeptCode($formData['applied_dept'],$formData['applied_subdept'],$formData['applied_office']);
        $deptDetails = DepartmentNew::where('dept_code',$formData['applied_dept'])
            ->where('office_code', $formData['applied_subdept'])
            ->where('suboffice_code' , $formData['applied_office'])
            ->first();

        $dateString = Carbon::now()->format('dmy');

        // 4. Handle file upload
        if ($request->hasFile('document')) {
            $folderPath = 'public/New_Request_Documents/' .  $deptDetails->dept_code;
            if (!Storage::exists($folderPath)) Storage::makeDirectory($folderPath);

            $file = $request->file('document');
            $fileName = $deptDetails->dept_code .'_'. $dateString .'_'.$id.'.'. $file->getClientOriginalExtension();
            $path = Storage::putFileAs($folderPath, $file, $fileName);

            $formData['document'] = $path;
            $formData['document_path'] = $path;
            $formData['document_name'] = $fileName;
            $formData['document_name_original'] = $file->getClientOriginalName();
        }

        // 5. Process edu_status
        $formData['edu_status'] = $formData['edu_status'] === 'educated' ? 1 : 0;
        $formData['qualification'] = $formData['edu_status'] ? ($formData['qualification'] ?? 0) : 0;

        // 6. Process BPL
        if ($formData['is_bpl'] == 'yes') {
            $formData['is_bpl'] = 1;
            $actionChange = 'RTI APPLICATION SUBMITTED';
        } else {
            $formData['is_bpl'] = 0;
            $actionChange = 'UPDATED ENTRY; PLEASE VERIFY AND UPDATE/PAY RTI FEES';
            $formData['card_no'] = 0;
            $formData['issued_year'] = 0000;
            $formData['issuing_authority'] = 'NA';
        }

        // 7. Update the record
        $fillableFields = $submitRequestObj->getFillable();
        $dataToUpdate = array_intersect_key($formData, array_flip($fillableFields));
        $submitRequestObj->update($dataToUpdate);

        // 8. Update action_details & application_history
        $ipAddress = $request->ip();
        $deptDataNodal = dept_officers_master::where('dept_code', $deptDetails->dept_code)->get();
        $deptData = dept_officers_master::where('dept_id',$deptCode)->get();

        $nodalData = $deptDataNodal->where('role', 1)->first();
        $pioData = $deptData->where('role', 2)->first();
        $apioData = $deptData->where('role', 3)->first();
        $assignedOfficer2 = is_null($pioData)? $apioData : $pioData;

        $actionDetailsData = [
            'reg_no' => $submitRequestObj->reg_no,
            'status_id' => 1,
            'assigned_off1' => $nodalData ? $nodalData->id : null,
            'assigned_off2' => $assignedOfficer2->id,
            'updated_by' => 8,
            'ip_address' => $ipAddress,
            'mac_address' => 'N/A',
        ];
        $actionDetailsId = action_details::create($actionDetailsData);

        $historyData = [
            'reg_no' => $submitRequestObj->reg_no,
            'changes' => $actionChange,
            'history_field' => json_encode($dataToUpdate),
            'action_details_id' => $actionDetailsId->id,
            'current_dept_id' => $deptCode,
        ];
        application_history::create($historyData);

        Session::put('data',$submitRequestObj);

        // 9. Redirect logic / generate reg_no if BPL
        if($submitRequestObj->is_bpl == 0){
            return redirect()->route('payment');
        } else {
            $departmentInfo = Rti_Dept_Id::where('dept_id', $deptCode)->first();
            if ($submitRequestObj && $departmentInfo) {
                $nextSequenceValue = DB::select("SELECT NEXTVAL('main_id')")[0]->nextval;
                $newRegNumber = $submitRequestObj->genRegNumber($departmentInfo->pubauthcode, date('m').date('y'), $nextSequenceValue);

                $submitRequestObj->update([
                    'reg_no' => $newRegNumber,
                    'payment_details' => 'Success'
                ]);

                RtiApplicantRegdata::where('reg_no', $submitRequestObj->id)->update(['reg_no' => $newRegNumber]);

                $actionDetailsUpdate = action_details::where('reg_no', $submitRequestObj->id)->first();
                if ($actionDetailsUpdate) {
                    $actionDetailsUpdate->reg_no = $newRegNumber;
                    $actionDetailsUpdate->save();
                }

                $historyUpdate = application_history::where('reg_no', $submitRequestObj->id)->first();
                if($historyUpdate){
                    $historyUpdate->reg_no = $newRegNumber;
                    $historyUpdate->save();
                }

                // SMS notifications
                $roleName = ($assignedOfficer2->role == 2) ? 'PIO' : 'APIO';
                $this->_sendSms($assignedOfficer2->mobile_no, "Dear Sir/Madam, RTI New Request registration number $newRegNumber has been assigned to your $roleName ID - Dept. of Information and Publicity");
                $this->_sendSms($formData['mobile_no'], "Your RTI request has been filed vide registration number $newRegNumber - Dept. of Information and Publicity");
            }

            return view('successFiling', compact('submitRequestObj','newRegNumber','departmentInfo'));
        }
    }

    public function newForm()
    {
        $stateData = State::where('id', 6)->get();

        $profileData = Profile::where('mobile_no', session('mobile_no'))->first();

        $deptData = DepartmentNew::where(['office_code' =>0, 'suboffice_code' => 0, 'office' => 'NA', 'suboffice' => 'NA'])->get();
        // dump(json_encode($stateData));
        return view('submit-req-form',compact('stateData','deptData','profileData'));
    }

    public function newFormSubmit(Request $request)
    {
            // 1. Honeypot Check
        if ($request->filled('middle_name')) {
            // Log the bot attempt if you want
            \Log::info("Bot caught by honeypot: " . $request->ip());

            // Return success so the bot thinks it won, but don't save anything
            return back()->with('success', 'Form submitted successfully!');
        }

        $validator = Validator::make($request->all(), [
        //'g-recaptcha-response' => [new ReCaptcha()],
        'applied_dept' => 'required',
        'applied_subdept' => 'required',
        'applied_office' => 'required',
        'name' => 'required|regex:/^[a-zA-Z\s]+$/',
        'gender' => 'required',
        'address_line1' => 'required|max:50',
        'address_line2' => 'required|max:50',
        'address_line3' => 'required|max:50',
        'pincode' => 'required|numeric|digits:6',
        'country' => 'required',
        'state' => 'required',
        'district' => 'required',
        'taluka' => 'required',
        'village' => 'required',
        'mobile_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
        'email_id' => 'required|email',
        'edu_status' => 'required',
        'qualification' => 'required_if:edu_status,educated',
        'origin_country' => 'required',
        'is_bpl' => 'required',
        'request_text' => 'string|max:3000',
        'document' => 'nullable|file|max:5120|mimetypes:application/pdf', // Optional file, max 5 MB, only PDF
        'captcha'   => ['required', new CaptchaValid],
    ]);

    $validator->after(function ($validator) use ($request) {
            // Get the values of address fields
            $address_line1 = $request->input('address_line1');
            $address_line2 = $request->input('address_line2');
            $address_line3 = $request->input('address_line3');

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

    // Check if the validation fails
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

        $formData = $request->all();

        $actionChange = null;
        $submitRequestObj = new SubmitRequest();
        $deptCode = $submitRequestObj->createDeptCode($formData['applied_dept'],$formData['applied_subdept'],$formData['applied_office']);
        $deptDetails = DepartmentNew::where('dept_code',$formData['applied_dept'])
        ->where('office_code', $formData['applied_subdept'])
        ->where('suboffice_code' , $formData['applied_office'])
        ->first();

        $date = Carbon::now();
        $dateString = $date->format('dmy');

        // dump($formData);
        if ($request->hasFile('document')) {
            $folderPath = 'public/New_Request_Documents/' .  $deptDetails->dept_code;
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
            }

            $file = $request->file('document');
            $originalFileName = $file->getClientOriginalName();
            $lastInsertedId = SubmitRequest::orderBy('id', 'desc')->first();

            $currentId = is_null($lastInsertedId) ? 1 : $lastInsertedId->id + 1;
            $fileName = $deptDetails->dept_code .'_'. $dateString .'_'.$currentId.'.'. $file->getClientOriginalExtension();

            $path = Storage::putFileAs(
                $folderPath, $file, $fileName
            );

            $formData['document'] = $path;
            $formData['document_name'] = $fileName;
            $formData['document_path'] = $path;
            $formData['document_name_original'] = $originalFileName;
        } else {
            $lastInsertedId = SubmitRequest::orderBy('id', 'desc')->first();
            $currentId = is_null($lastInsertedId) ? 1 : $lastInsertedId->id + 1;
        }

        $formData['country'] = 1;
        $formData['origin_country'] = 1;
        if($formData['edu_status'] == 'educated')
        {
            $formData['edu_status'] = 1;
            if($request['qualification'] == null)
                $formData['qualification'] = 0;
        }
        else
        {
            $formData['edu_status'] = 0;
            $formData['qualification'] = 0;
        }

        if($formData['is_bpl'] == 'yes')
        {
            $formData['is_bpl'] = 1;
            $actionChange = 'RTI APPLICATION SUBMITTED';
        }
        else
        {
            $actionChange = 'NEW ENTRY CREATED; PLEASE VERIFY AND UPDATE/PAY RTI FEES';
            $formData['is_bpl'] = 0;
            $formData['card_no'] = 0;
            $formData['issued_year'] = 0000;
            $formData['issuing_authority'] = 'NA';
        }

        if($formData['phone_no']== '')
        {
            $formData['phone_no'] = 0000000;
        }
        $formData['dept_id'] = $deptCode;

        $submitReqData = new SubmitRequest();

        $regNo = $currentId;
        $formData['reg_no'] = $regNo;

        $fillableFields = $submitReqData->getFillable();
        $dataToInsert = array_intersect_key($formData, array_flip($fillableFields));
        $dataToInsert['dept_id'] = $deptCode;
        // dump($fillableFields);
        // dd($formData);

        $submissionId = SubmitRequest::create($formData);

        $ipAddress = $request->ip();

        $deptDataNodal = dept_officers_master::where('dept_code', $deptDetails->dept_code)->get();
        $deptData = dept_officers_master::where('dept_id',$deptCode)->get();

        $nodalData = $deptDataNodal->where('role', 1)->first();
        // $pioData = $deptData->where('role', 2)->where('office_code', $deptDetails->office_code)->first();
        $pioData = $deptData->where('role', 2)->first();
        // $apioData = $deptData->where('role', 3)->where('suboffice_code', $deptDetails->office_code)->first();
        $apioData = $deptData->where('role', 3)->first();
        // $firstAppletData = $deptData->where('role', 4)->where('office_code', $deptDetails->office_code)->first();
        $firstAppletData = $deptData->where('role', 4)->first();
        // dd($roleOneData,$roleTwoData,$roleThreeData,$roleFourData,$roleFiveData);

        $assignedOfficer2 = is_null($pioData)? $apioData : $pioData;

        $actionDetailsData = [
            'reg_no' => $formData['reg_no'],
            'status_id' => 1,
            'assigned_off1' => $nodalData ? $nodalData->id : null,
            'assigned_off2' => $assignedOfficer2->id,
            'updated_by' => 8,
            'ip_address' => $ipAddress,
            'mac_address' => 'N/A',
        ];
        $actionDetailsId = action_details::create($actionDetailsData);

        $historyData = [
            'reg_no' => $formData['reg_no'],
            'changes' => $actionChange,
            'history_field' => json_encode($dataToInsert),
            'action_details_id' => $actionDetailsId->id,
            'current_dept_id' => $deptCode,
        ];

        application_history::create($historyData);

        $applicantRegData = [
            'dept_id' => $deptCode,
            'reg_no' => $regNo,
            'regmobile_no' => session('mobile_no'),
            'email_id' => $formData['email_id'],
        ];

        RtiApplicantRegdata::create($applicantRegData);

        // return back()->with('success', 'Form has been submitted<br>Registration Number: ' . $regNo);
        Session::put('data',$submissionId);

        // dd($submissionId->is_bpl);
        if($submissionId->is_bpl == 0){
            return redirect()->route('payment');
        }
        else
        {
            $departmentInfo = Rti_Dept_Id::where('dept_id', $deptCode)->first();
            if ($submissionId) {
                if ($departmentInfo) {
                    $nextSequenceValue = DB::select("SELECT NEXTVAL('main_id')")[0]->nextval;

                    $newRegNumber = $submissionId->genRegNumber($departmentInfo->pubauthcode, date('m').date('y'), $nextSequenceValue);

                    $submissionId->update([
                        'reg_no' => $newRegNumber,
                        'payment_details' => 'Success'
                    ]);

                    $rtiApplicantRegDataUpdate = RtiApplicantRegdata::where('reg_no', $regNo)->update(['reg_no' => $newRegNumber]);

                    $actionDetailsData = action_details::where('reg_no', $submissionId->id)->first();
                    if ($actionDetailsData) {
                        $actionDetailsData->reg_no = $newRegNumber;
                        $actionDetailsData->save();
                    }

                    $applicationHistoryData = application_history::where('reg_no', $submissionId->id)->first();
                    if($applicationHistoryData)
                    {
                        $applicationHistoryData->reg_no = $newRegNumber;
                        $applicationHistoryData->save();
                    }

                    // sms for PIO
                    $roleName = null;
                    if($assignedOfficer2->role == 2)
                        $roleName = 'PIO';
                    else if($assignedOfficer2->role == 3)
                        $roleName = 'APIO';

                    $var1 ="Sir/Madam";
                    $var2 = "New Request";
                    $var3 = "been received and assigned against your ".$roleName." ID";

                    $pioMsg = "Dear ".$var1.", RTI ".$var2." registration number ".$newRegNumber." has ".$var3." - Dept. of Information and Publicity";
                    $this->_sendSms($assignedOfficer2->mobile_no,$pioMsg);

                    // sms for applicant
                    $msg = "Your RTI New Request has been filed vide registration number ".$newRegNumber." - Dept. of Information and Publicity";
                    $this->_sendSms($formData['mobile_no'],$msg);
                }
            }
            return view('successFiling',compact('submissionId','newRegNumber','departmentInfo'));
            // return back()->with('success','Form has been submitted.'. PHP_EOL .'Registration Number : '.$newRegNumber);
        }

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


    /**
     * viewRequestDetails function
     *
     * @return void
     */
    public function viewRequestDetails()
    {
        return view('viewRequestDetails');
    }

    /**
     * getDistrict function
     *
     * @param [int] $stateId
     * @return void
     */
    public function getDistrict($stateId)
    {
        $districtData = District::where('state_id',$stateId)->get();
        // dd($districtData);
        return response()->json($districtData);
    }

    /**
     * getTaluka function
     *
     * @param [int] $districtId
     * @return void
     */
    public function getTaluka($districtId)
    {
        $talukaData = Taluka::where('district_id',$districtId)->get();
        return response()->json($talukaData);
    }

    /**
     * getVillage function
     *
     * @param [int] $talukaId
     * @return void
     */
    public function getVillage($talukaId)
    {
        $villageData = Village::where('taluka_id',$talukaId)->get();
        return response()->json($villageData);
    }

    public function getSubDept($deptId)
    {
        $subDeptData = DepartmentNew::where('dept_code',$deptId)->orderby('office_code', 'asc')
        // ->where('office_code' ,'!=', 0)  commented for inlusion of the same sub dept as well
        ->where('suboffice_code',0)
        ->select('department','dept_code','office_code','suboffice_code')
        ->get();

        // dump($subDeptData);
        return response()->json($subDeptData);
    }

    public function getOffice($deptId,$officeId)
    {
        $officeData = DepartmentNew::where('dept_code', $deptId)->where('office_code', $officeId)->orderby('suboffice_code', 'asc')
        // ->where('suboffice_code' ,'!=', 0)   commented for inclusion of the same sub office we well
        ->select('department','dept_code','office_code','suboffice_code')
        ->get();

        return response()->json($officeData);
    }

    public function getPio($deptId, $subDeptId)
    {
        $pioUsers = dept_officers_master::join('rti_dept_id as rdi', 'dept_officers_master.dept_id', 'rdi.dept_id')
        ->where('dept_officers_master.dept_code',$deptId)
        // ->where('office_code',$subDeptId)
        ->where('is_active',1)
        ->whereIn('role',[2,3])
        ->select('dept_officers_master.id', 'dept_officers_master.username', 'dept_officers_master.dept_id','dept_officers_master.name', 'dept_officers_master.designation','dept_officers_master.mobile_no', 'dept_officers_master.address1', 'rdi.department')
        ->get();

        return response()->json($pioUsers);
    }

    public function getFaa($deptId, $subDeptId)
    {
        $faaUsers = dept_officers_master::join('rti_dept_id as rdi', 'dept_officers_master.dept_id', 'rdi.dept_id')
        ->where('dept_officers_master.dept_code',$deptId)
        // ->where('office_code',$subDeptId)
        ->where('is_active',1)
        ->where('role',4)
        ->select('dept_officers_master.id', 'dept_officers_master.username', 'dept_officers_master.dept_id','dept_officers_master.name', 'dept_officers_master.designation','dept_officers_master.mobile_no', 'dept_officers_master.address1', 'rdi.department')
        ->get();

        return response()->json($faaUsers);
    }

    public function getDepartment()
    {
        return view('searchDepartment');
    }

    public function showDepartment(Request $request)
    {
        $search = strtolower(trim($request->input('search')));

        $departments = Rti_Dept_Id::whereRaw('LOWER(department) LIKE ?', ["%$search%"])->get();

        $hierarchy = [];

        foreach ($departments as $department)
        {
            $deptDetails = DB::table('dept_master')
                ->where('dept_code', $department->dept_code)
                ->where('office_code', 0)
                ->where('suboffice_code', 0)
                ->first();

            $officeDetails = DB::table('office_master')
                ->where('dept_code', $department->dept_code)
                ->where('office_code', $department->office_code)
                ->where('suboffice_code', 0)
                ->first();

            $subOfficeDetails = DB::table('suboffice_master')
                ->where('dept_code', $department->dept_code)
                ->where('office_code', $department->office_code)
                ->where('suboffice_code', $department->suboffice_code)
                ->first();


            $hierarchy[] =
            [
                'department_row' => $department,
                'dept_details' => $deptDetails,
                'suboffice_details' => $subOfficeDetails,
                'office_details' => $officeDetails,
            ];
        }

        return view('searchDepartment', compact('departments', 'search', 'hierarchy'));
    }

    public function checkPioStatus($dept, $office = 0, $suboffice = 0)
    {
        // Query the dept_officers_master table for role = 2 (PIO)
        $pio = DB::table('dept_officers_master')
            ->where('dept_code', $dept)
            ->where('office_code', $office)
            ->where('suboffice_code', $suboffice)
            ->where('role', 2)
            ->first();

        // Check if PIO exists and active is 0
        $isInactive = !$pio || $pio->is_active == 0;

        return response()->json(['inactive' => $isInactive]);
    }

}
