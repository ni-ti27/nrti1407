<?php

namespace App\Http\Controllers;
use App\Models\DepartmentNew;
use App\Models\State;
use App\Models\District;
use App\Models\Taluka;
use App\Models\SubmitRequest;
use App\Models\Village;
use App\Models\Rti_Dept_Id;
use App\Models\fa_submit_request;
use App\Models\sa_submit_request;
use App\Models\sa_application_history;
use App\Models\sa_action_details;
use App\Models\SA_Notice;
use App\Models\pioResponse;
use App\Models\Role;
use App\Models\Actions;
use App\Models\action_details;
use App\Models\Role_Action;
use App\Models\sa_uploaded_documents;
use App\Models\fa_uploaded_documents;
use App\Models\saJudgement;
use App\Models\dept_officers_master;
use App\Models\SA_Reply_To_Applicant;
use App\Models\FaActionDetails;
use Illuminate\Support\Facades\DB;
use App\Models\fa_application_status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Rules\CaptchaValid;

class SecondAppealController extends Controller
{
    public function requestCheck()
    {
        return view('SecondAppeal.ReqTypeForm');
    }

    public function index()
    {
        return view('SecondAppeal.form');
    }

    public function displayForm()
    {
        return view('SecondAppeal.form');
    }

    public function displayOnlineForm()
    {
        $fa_reg_no = session('fa_registration_for_sa');

        try
        {
            $saRequest = sa_submit_request::where(['fa_reg_no' => $fa_reg_no])->first();
            if($saRequest)
                throw new exception('Second Appeal Already filed for this First Appeal');

            $faRequestDetails = fa_submit_request::with('uploadedDocuments.documentCategory','pioResponseData','FaActionDetails', 'FaActions','faRegistration')->where('fa_reg_no', 'LIKE', '%' . "%{$fa_reg_no}%")->first();

            if($faRequestDetails == null)
                throw new Exception('First Appeal Request Does Not Exists');

            $documentCategory = null;
            $uploadedDocuments = $faRequestDetails->uploadedDocuments;
            $filePaths = [];
            foreach ($uploadedDocuments as $document)
            {
                if ($document->document_path)
                {
                    // dd($document->document_category);
                    $filePath = Storage::url($document->document_path);
                    if($document->document_category == 2)
                    {
                        $documentCategory = 'First Appeal';
                        $filePaths[$documentCategory] = $filePath;
                        break;
                    }
                    if($document->document_category == 4 || $document->document_category == 5)
                    {
                        $documentCategory = 'Support';
                        $filePaths[$documentCategory] = $filePath;
                    }
                    else if($document->document_category == 7 )
                    {
                        $documentCategory = 'Pio Reply';
                        $filePaths[$documentCategory] = $filePath;
                    }
                    else
                    {
                        $documentCategory = 'Unknown';
                        $filePaths[$documentCategory] = $filePath;
                    }
                }
            }

            if($documentCategory == 'Unknown')
                throw new exception('First Appeal Document not present');

            $requestStatus = fa_application_status::join('dept_officers_master','fa_application_statuses.assigned_off2','dept_officers_master.id')
            ->join('application_status', 'fa_application_statuses.status_id', '=', 'application_status.id')
            ->where('fa_reg_no',$faRequestDetails['fa_reg_no'])
            ->select('fa_application_statuses.*', 'dept_officers_master.id as id', 'dept_officers_master.name as name', 'dept_officers_master.address1 as address1','application_status.status as status')
            ->first();

            $data = [];

            if ($faRequestDetails->state == 0)
            {
                $data['stateData'] = (object)[
                    'id' => 0,
                    'name' => 'Others'
                ];
            }
            else
            {
                    $data['stateData'] = State::where('id',$faRequestDetails->state)->first();
            }
            $data['district'] = District::where('id',$faRequestDetails->district)->first();
            $data['taluka'] = Taluka::where('id',$faRequestDetails->taluka)->first();
            $data['village'] = Village::where('id',$faRequestDetails->village)->first();

            $data['submitReq'] = SubmitRequest::where('reg_no', $faRequestDetails->reg_no)->first();
            $filePath = $data['submitReq']['document_path'];
            $data['filePath'] = Storage::url($filePath);

            $today = \Carbon\Carbon::now();
            $createdDate = \Carbon\Carbon::parse($faRequestDetails['created_at'])->startOfDay();

            $daysSinceSubmission = $createdDate->diffInDays($today);

            $faActionDetails = FaActionDetails::select('id', 'created_at')
            ->where('fa_reg_no', $faRequestDetails['fa_reg_no'])
            ->where('changes', 'APPEAL DISPOSE OF')
            ->orderBy('id', 'desc')
            ->first();


            $allowFiling = true;
            $errorMessage = "";

            if ($faActionDetails) {
                $disposalDate = \Carbon\Carbon::parse($faActionDetails->created_at);
                $daysSinceDisposal = $disposalDate->diffInDays($today);
                // RULE: If disposed, only the 90-day limit matters
                if ($daysSinceDisposal > 90) {
                    $allowFiling = false;
                    $errorMessage = "Second Appeal cannot be filed. First Appeal Disposal date exceeds 90 days.";
                }
            } else {
                // RULE: If not disposed, enforce the 30-day waiting period
                if ($daysSinceSubmission < 31) {
                    $allowFiling = false;
                    $errorMessage = "Second Appeal can be filed only after 30 days of First Appeal Submission.";
                }
            }

            if (!$allowFiling) {
                throw new exception($errorMessage);
            }

            $deptData = DepartmentNew::where('dept_code', $faRequestDetails['applied_dept'])
                    ->where('office_code', 0)
                    ->where('suboffice_code',0)
                    ->orderBy('office_code')
                    ->orderBy('suboffice_code')
                    ->first();

            $officeData = DepartmentNew::where('dept_code', $faRequestDetails['applied_dept'])
                    ->where('office_code', $faRequestDetails['applied_subdept'])
                    ->where('suboffice_code',0)
                    ->orderBy('office_code')
                    ->orderBy('suboffice_code')
                    ->first();

            $subOfficeData = DepartmentNew::where('dept_code', $faRequestDetails['applied_dept'])
                    ->where('office_code', $faRequestDetails['applied_subdept'])
                    ->where('suboffice_code',$faRequestDetails['applied_office'])
                    ->orderBy('office_code')
                    ->orderBy('suboffice_code')
                    ->first();

            $data['deptData'] = [
                'applied_dept' => $deptData,
                'applied_subdept' => $officeData,
                'applied_office' => $subOfficeData
            ];

            return view('SecondAppeal.OnlineRequestForm',compact('faRequestDetails','requestStatus','data','filePaths'));
        }
        catch (Exception $ex)
        {
            return back()->with('warning', $ex->getMessage());
        }
    }

    public function displayOfflineForm()
    {
        $deptData = DepartmentNew::where(['office_code' =>0, 'suboffice_code' => 0, 'office' => 'NA', 'suboffice' => 'NA'])->get();
        $natureOfResponse = pioResponse::get();

        return view('SecondAppeal.OfflineForm',compact('deptData','natureOfResponse'));
    }

    public function handleFormType(Request $req)
    {
        $this->validate($req,[
            'request_type' => 'required',
        ]);

        if($req->request_type == 'online')
            return redirect()->route('sa-form1-Form');
        else
            return redirect()->route('sa-offline-form');
    }

    public function handleform1(Request $request)
    {
        $this->validate($request, [
            'fa_reg_no' => 'required|string|regex:/\/FA\//',
            'emailId' => 'required|regex:/^[\w\._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
        ], [
            'fa_reg_no.regex'   => 'Invalid Registration No.',
            'fa_reg_no.required' => 'The registration number is required.',
            'fa_reg_no.string' => 'The registration number must be a string.',
            'emailId.required' => 'The email id field is required.',
            'emailId.email' => 'Enter a valid email id.',
        ]);

        Session::put('fa_registration_for_sa', $request['fa_reg_no']);

        return redirect()->route('sa-online-form')
        ->with('fa_reg_no', $request->fa_reg_no);
    }

    public function secondAppealOnlineSave(Request $request)
    {
        try
        {
            DB::beginTransaction();

                        // 1. Honeypot Check
            if ($request->filled('middle_name')) {
                // Log the bot attempt if you want
                \Log::info("Bot caught by honeypot: " . $request->ip());

                // Return success so the bot thinks it won, but don't save anything
                return back()->with('success', 'Form submitted successfully!');
            }
            $request->validate([
                'captcha'   => ['required', new CaptchaValid],], [
                // Custom error messages for a better user experience
                'captcha.required' => 'Please enter the security code.',

            ]);

            $submitRequestObj = new SubmitRequest;
            $deptCode = $submitRequestObj->createDeptCode($request['applied_dept'], $request['applied_subdept'], $request['applied_office']);

            $saObject = new sa_submit_request();
            $nextSequenceValue = DB::select("SELECT NEXTVAL('main_sa_id')")[0]->nextval;
            $deptDetails = Rti_Dept_Id::where('dept_id', $deptCode)->first();
            $saRegNo = $saObject->genRegNumber($deptDetails->pubauthcode,date('m').date('y'),$nextSequenceValue);

            if($request['phone_no'] == '' || $request['phone_no'] == 0)
                $request['phone_no'] = 0;

            if($request->hasFile('sa_application_doc'))
            {
                $fileFaaDoc = $request->file('sa_application_doc');
                if (!$fileFaaDoc->isValid())
                    throw new Exception('FAA Reply Document is not a valid file.');
            }
            else
                throw new exception('FAA Reply Document Required');

            if($request->hasFile('id_proof_file'))
            {
                $fileIdProof = $request->file('id_proof_file');
                if (!$fileIdProof->isValid())
                    throw new Exception('ID Proof Document is not a valid file.');
            }
            else
                throw new exception('ID Proof Document Required');

            $saObject->create([
                'applied_dept' => $request['applied_dept'],
                'applied_subdept' => $request['applied_subdept'],
                'applied_office' => $request['applied_office'],
                'dept_id' => $deptCode,

                'reg_no' => $request['reg_no'],
                'fa_reg_no' => $request['fa_reg_no'],
                'sa_reg_no' => $saRegNo,

                // Personal Information
                'name' => $request['name'],
                'gender' => $request['gender'],
                'phone_no' => $request['phone_no'],
                'mobile_no' => $request['mobile_no'],
                'email_id' => $request['email_id'],

                // Address Information
                'address_line1' => $request['address_line1'],
                'address_line2' => $request['address_line2'],
                'address_line3' => $request['address_line3'],
                'pincode' => $request['pincode'],
                'country' => $request['country'],
                'state' => $request['state'],
                'district' => $request['district'],
                'taluka' => $request['taluka'],
                'village' => $request['village'],

                // Additional Information
                'online_request' => true,
                'newrequest_date' => $request['hidden_reg_date'],

                // PIO Information
                'pio_id' => $request['pio_id'],
                'pio_name' => $request['pio_name'],
                'pio_address' => $request['pio_address'],
                'pio_response' => $request['pio_response_display'],

                // FAA Information
                'faa_date' => $request['hidden_faa_date'],
                'faa_id' => $request['faa_id'],
                'faa_name' => $request['faa_name'],
                'faa_address' => $request['faa_address'],
                'faa_response' => $request['faa_resp_dropdown'],
            ]);

            $folderPath = 'public/Second_Appeal_Documents/' . substr($deptDetails->dept_id, 0, 2);
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
            }

            // Define the mapping of input names to document categories and supporting text inputs
            $fileCategories = [
                'rti_application_doc' => 1,
                'fa_application_doc' => 2,
                'sa_application_doc' => 3,
                'id_proof_file' => 6,
                'sa_document1' => 4,
                'sa_document2' => 5,
            ];

            // Iterate through the file categories and process uploads
            foreach ($fileCategories as $inputName => $category) {
                if ($request->hasFile($inputName)) {
                    // Determine the supporting text input name based on the file input name
                    $supportingTextInputName = $inputName . '_supporting_text';
                    $supportingText = $request->input($supportingTextInputName, null); // Default to null if not provided

                    $this->uploadDocument($deptDetails, $request->file($inputName), $folderPath, $saRegNo, $category, $supportingText);
                }
            }

            $ipAddress = $request->ip();
            // dd($deptDetails->dept_code, $deptCode);
            $clerk = dept_officers_master::where('role',5)->where('is_active',1)->first();
            $stateInfoComissioner = dept_officers_master::where('role',6)->where('is_active',1)->first();

            $saActionDetailsData = [
                'reg_no' => $saRegNo,
                'status_id' => 11,
                'assigned_off1' => $clerk->id,
                'assigned_off2' => $stateInfoComissioner->id,
                'updated_by' => null,
                'ip_address' => $ipAddress,
                'action_id' => 1,
            ];
            $saActionSave = sa_action_details::create($saActionDetailsData);

            $currentActionDetailsArray = $saActionSave->toArray();
            $saActionDetailsFillable = $saActionSave->getFillable();
            $dataToInsert = array_intersect_key($currentActionDetailsArray, array_flip($saActionDetailsFillable));

            $saHistoryData = [
                'sa_reg_no' => $saRegNo,
                'changes_id' => 1,
                'sa_action_details_id' => $saActionSave->id,
                'history_field' => json_encode($dataToInsert),
                'current_dept_id' => $deptCode,
            ];
            sa_application_history::create($saHistoryData);
            DB::commit();

            // sms for officer
            $roleName = 'Clerk';

            $var1 = "Sir/Madam";
            $var2 = "New Second Appeal Request";
            $var3 = "been received and assigned against your ".$roleName." ID";

            $clerkMsg = "Dear ".$var1.", RTI ".$var2." registration number ".$saRegNo." has ".$var3." - Dept. of Information and Publicity";
            $this->_sendSms($clerk->mobile_no,$clerkMsg);

            // sms for applicant
            $msg = "Your RTI New Second Appeal Request has been filed vide registration number ".$saRegNo." - Dept. of Information and Publicity";
            $this->_sendSms($request['mobile_no'],$msg);


            return redirect('/SA-form')->with('success', 'Second Appeal Request Filed Succesfully');
        }
        catch(Exception $ex)
        {
            DB::rollback();
            return back()->with('warning', $ex->getMessage());
        }
    }

    public function secondAppealOfflineSave(Request $request)
    {
        // dd($request->all());
        try
        {
            DB::beginTransaction();

            // 1. Honeypot Check
            if ($request->filled('middle_name')) {
                // Log the bot attempt if you want
                \Log::info("Bot caught by honeypot: " . $request->ip());

                // Return success so the bot thinks it won, but don't save anything
                return back()->with('success', 'Form submitted successfully!');
            }

            $request->validate([
                'captcha'   => ['required', new CaptchaValid],], [
                // Custom error messages for a better user experience
                'captcha.required' => 'Please enter the security code.',

            ]);
            $submitRequestObj = new SubmitRequest;
            $deptCode = $submitRequestObj->createDeptCode($request['applied_dept'], $request['applied_subdept'], $request['applied_office']);

            $saObject = new sa_submit_request();
            $nextSequenceValue = DB::select("SELECT NEXTVAL('main_sa_id')")[0]->nextval;
            $deptDetails = Rti_Dept_Id::where('dept_id', $deptCode)->first();
            $saRegNo = $saObject->genRegNumber($deptDetails->pubauthcode,date('m').date('y'),$nextSequenceValue);

            if($request['phone_no'] == '' || $request['phone_no'] == 0)
            {
                $request['phone_no'] = 0;
            }

            if($request->hasFile('fa_application_doc'))
            {
                $fileFaaDoc = $request->file('fa_application_doc');
                if (!$fileFaaDoc->isValid())
                    throw new Exception('FAA Reply Document is not a valid file.');
            }
            else
                throw new exception('FAA Reply Document Required');

            if($request->hasFile('id_proof_file'))
            {
                $fileIdProof = $request->file('id_proof_file');
                if (!$fileIdProof->isValid())
                    throw new Exception('ID Proof Document is not a valid file.');
            }
            else
                throw new exception('ID Proof Document Required');

            $pioDetails = dept_officers_master::where('id',$request->pio_name)
                ->whereIn('role',[2,3])
                ->where('is_active',1)->first();

            $faaDetails = dept_officers_master::where('id',$request->faa_name)
                ->where('role',4)
                ->where('is_active',1)->first();

            $saObject->create([
                'applied_dept' => $request['applied_dept'],
                'applied_subdept' => $request['applied_subdept'],
                'applied_office' => $request['applied_office'],
                'dept_id' => $deptCode,

                'reg_no' => $request['reg_no'],
                'fa_reg_no' => $request['fa_reg_no'],
                'sa_reg_no' => $saRegNo,

                // Personal Information
                'name' => $request['name'],
                'gender' => $request['gender'],
                'phone_no' => $request['phone_no'],
                'mobile_no' => $request['mobile_no'],
                'email_id' => $request['email_id'],

                // Address Information
                'address_line1' => $request['address_line1'],
                'address_line2' => $request['address_line2'],
                'address_line3' => $request['address_line3'],
                'pincode' => $request['pincode'],
                'country' => $request['country'],
                'state' => $request['state'],
                'district' => $request['district'],
                'taluka' => $request['taluka'],
                'village' => $request['village'],

                // Additional Information
                'online_request' => false,
                'newrequest_date' => $request['reg_date'],

                // PIO Information
                'pio_id' => $pioDetails['id'],
                'pio_name' => $pioDetails['name'],
                'pio_address' => $pioDetails['address1'],
                'pio_response' => $request['pio_response_display'],

                // FAA Information
                'faa_date' => $request['fa_reg_date'],
                'faa_id' => $faaDetails['id'],
                'faa_name' => $faaDetails['name'],
                'faa_address' => $faaDetails['address1'],
                'faa_response' => $request['faa_resp_dropdown'],
            ]);

            // dump('control is here part 5');

            $folderPath = 'public/Second_Appeal_Documents/' . substr($deptDetails->dept_id, 0, 2);
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
            }

            // Define the mapping of input names to document categories and supporting text inputs
            $fileCategories = [
                'rti_application_doc' => 1,
                'fa_application_doc' => 2,
                'sa_application_doc' => 3,
                'id_proof_file' => 6,
                'sa_document1' => 4,
                'sa_document2' => 5,
            ];

            if($request['user_text'] != '')
            {
                Session::put('rtiRequestText', $request['user_text']);
            }

            if($request['faa_requested_text'] != '')
            {
                Session::put('faaRequestText', $request['faa_requested_text']);
            }

            // Iterate through the file categories and process uploads
            foreach ($fileCategories as $inputName => $category) {
                if ($request->hasFile($inputName)) {
                    // Determine the supporting text input name based on the file input name
                    $supportingTextInputName = $inputName . '_supporting_text';
                    $supportingText = $request->input($supportingTextInputName, null);

                    $this->uploadDocument($deptDetails, $request->file($inputName), $folderPath, $saRegNo, $category, $supportingText);
                }
            }

            $ipAddress = $request->ip();
            // dd($deptDetails->dept_code, $deptCode);
            $clerk = dept_officers_master::where('role',5)->where('is_active',1)->first();
            $stateInfoComissioner = dept_officers_master::where('role',6)->where('is_active',1)->first();

            $saActionDetailsData = [
                'reg_no' => $saRegNo,
                'status_id' => 11,
                'assigned_off1' => $clerk->id,
                'assigned_off2' => $stateInfoComissioner->id,
                'updated_by' => null,
                'ip_address' => $ipAddress,
                'action_id' => 1,
            ];
            $saActionSave = sa_action_details::create($saActionDetailsData);

            $currentActionDetailsArray = $saActionSave->toArray();
            $saActionDetailsFillable = $saActionSave->getFillable();
            $dataToInsert = array_intersect_key($currentActionDetailsArray, array_flip($saActionDetailsFillable));

            $saHistoryData = [
                'sa_reg_no' => $saRegNo,
                'changes_id' => 1,
                'sa_action_details_id' => $saActionSave->id,
                'history_field' => json_encode($dataToInsert),
                'current_dept_id' => $deptCode,
            ];
            sa_application_history::create($saHistoryData);
            DB::commit();

            // sms for officer
            $roleName = 'Clerk';

            $var1 = "Sir/Madam";
            $var2 = "New Second Appeal Request";
            $var3 = "been received and assigned against your ".$roleName." ID";

            $clerkMsg = "Dear ".$var1.", RTI ".$var2." registration number ".$saRegNo." has ".$var3." - Dept. of Information and Publicity";
            $this->_sendSms($clerk->mobile_no,$clerkMsg);

            // sms for applicant
            $msg = "Your RTI New Second Appeal Request has been filed vide registration number ".$saRegNo." - Dept. of Information and Publicity";
            $this->_sendSms($request['mobile_no'],$msg);

            return redirect('/SA-form')->with('success', 'Second Appeal Request Filed Succesfully');
        }
        catch(Exception $ex)
        {
            DB::rollback();
            return back()->with('warning', $ex->getMessage());
        }

    }

    public function saRequestDetailedView($id)
    {
        try
        {
            $username = session('username');
            $userDetails['name'] = $username;

            $deptOfficer = dept_officers_master::where('username', $username)
            ->where('is_active', 1)->first();

            $roleDetails = Role::where('id',$deptOfficer->role)->first();
            $userDetails['role_name'] = $roleDetails->name;
            // $deptOfficer = $userDetails->deptOfficersMaster;

            $saSubmitRequest = sa_submit_request::with('state', 'village', 'district', 'saActionDetails','faaResponseData')->find($id);

            $departmentDetails1 = DepartmentNew::where('dept_code',$saSubmitRequest->applied_dept)->where('office_code', 0)->where('suboffice_code',0)->first();

            $departmentDetails2 = DepartmentNew::where('dept_code',$saSubmitRequest->applied_dept)->where('office_code', $saSubmitRequest->applied_subdept)->where('suboffice_code',0)->first();

            $departmentDetails3 = DepartmentNew::where('dept_code',$saSubmitRequest->applied_dept)->where('office_code', $saSubmitRequest->applied_subdept)->where('suboffice_code',$saSubmitRequest->applied_office)->first();

            $fullDeptData = [
                'department' => $departmentDetails1,
                'subDepartment' => $departmentDetails2,
                'officeDepartment' => $departmentDetails3,
            ];

            $statusId = $saSubmitRequest->saActionDetails->first()->status_id;
            $actionIds = Role_Action::where('role_id', $deptOfficer->role)->pluck('action_id');

            $assignedOfficers = action_details::where('reg_no', $saSubmitRequest->reg_no)->first();
            if(isset($assignedOfficers['assigned_off1']))
                $assignedOfficerData = dept_officers_master::where('id', $assignedOfficers['assigned_off2'])->where('is_active', 1)->first();
            else
                $assignedOfficerData = null;

            if($statusId == 11)
            {
                $idsAction = [19,20];
                $actions = Actions::whereIn('id', $idsAction)->orderBy('id')->get();
            }
            else
            {
                $idsAction = [21,22,23];
                $actions = Actions::whereIn('id', $actionIds)->whereIn('id',$idsAction)->orderBy('id')->get();
            }

            $clerkOfficers = dept_officers_master::where('is_active', 1)->where('role', 5)->first();
            $sicOfficers = dept_officers_master::where('is_active', 1)->where('role', 6)->first();

            $officersDetails = [
                'clerk' => $clerkOfficers,
                'sic' => $sicOfficers
            ];

            $requestedText = [];
            $getSAfiles = sa_uploaded_documents::where('sa_reg_no', $saSubmitRequest->sa_reg_no)->get();

            // dd($getSAfiles);
            if(!$getSAfiles->isEmpty())
            {
                $saTempFiles = [];
                foreach($getSAfiles as $sa)
                {
                    $saTempFiles[] = [
                        'document_category' => $sa['document_category'],
                        'document_name' => $sa['doc_name'],
                        'document_path' => Storage::url($sa['doc_path']),
                        'document_name_original' => $sa['doc_original_name'],
                        'meta_data' => $sa['meta_data'],
                    ];
                }

                $saFiles = [];
                foreach($saTempFiles as $file)
                {
                    if($file['document_category'] == 6)
                        $saFiles['idProof'] = $file;
                    else if($file['document_category'] == 4 || $file['document_category'] == 5)
                        $saFiles['supporting_doc'][] = $file;
                    else if($file['document_category'] == 3)
                        $saFiles['saFile'] = $file;
                    else if($file['document_category'] == 1)
                    {
                        $saFiles['newRequestFile'] = $file;
                        $requestedText['rti_req'] = json_decode($file['meta_data'],true);
                    }
                    else if($file['document_category'] == 2)
                    {
                        $saFiles['faRequestFile'] = $file;
                        $requestedText['faa_req'] = json_decode($file['meta_data'],true);
                    }
                }
            }

            // dd($requestedText, $saSubmitRequest->online_request);
            $requestDetails = [];
            $faRequestDetails = [];

            $faRequestDetails['pio_name'] = $saSubmitRequest->pio_name;
            $faRequestDetails['pio_add'] = $saSubmitRequest->pio_address;

            $faRequestDetails['faa_name'] = $saSubmitRequest->faa_name;
            $faRequestDetails['faa_add'] = $saSubmitRequest->faa_address;

            $faRequestDetails['created_at'] = $saSubmitRequest->faa_date;

            if($saSubmitRequest->online_request == true)
            {
                $requestDetails = SubmitRequest::where('reg_no',$saSubmitRequest->reg_no)->first();
                $requestDetails['filepath'] = Storage::url($requestDetails['document_path']);
                $requestedText['rti_req'] = $requestDetails['request_text'];
                // dd($requestDetails,$requestedText);
                $saFiles['newRequestFile'] =
                [
                    "document_category" => 1,
                    "document_name" => $requestDetails['document_name'],
                    "document_path" => $requestDetails['filepath'],
                    "document_name_original" => $requestDetails['document_name_original'],
                ];

                $faRequestDetails = fa_submit_request::with('pioResponseData')->where('reg_no',$saSubmitRequest->reg_no)->first();
                $faComplainResp = $faRequestDetails->pioResponseData;
                $faRequestDetails['faResponseReason'] = $faComplainResp->reason;

                $requestedText['faa_req'] = $faRequestDetails['requested_note'];
                $saSubmitRequest['saResponseReason'] = $saSubmitRequest->faaResponseData;

                $getFAfiles = fa_uploaded_documents::where('reg_no', $saSubmitRequest->reg_no)
                ->where('document_category',2)->first();

                if($getFAfiles )
                {
                    $saFiles['faRequestFile'] =
                [
                    "document_category" => 2,
                    "document_name" => $getFAfiles['document_name'],
                    "document_path" => Storage::url($getFAfiles['document_path']),
                    "document_name_original" => $getFAfiles['document_name_original'],
                ];

                }


                // dd($requestDetails['request_text'], $requestDetails['faa_req'], $requestedText);
            }

            return view('SecondAppeal.detailed-SA-Request', [
                'datajs' => [$saSubmitRequest->applied_dept, $saSubmitRequest->applied_subdept],
                'deptDetails' => $departmentDetails1->department,
                'officeDetails' => $departmentDetails2->department,
                'subOfficeDetails' => $departmentDetails3->department,
                'saSubmitRequest' => $saSubmitRequest,
                'assignedOfficerData' => $assignedOfficerData,
                'actionDetails' => $actions,
                'deptOfficer' => $deptOfficer,
                'userDetails' => $userDetails,
                'officersDetails' => $officersDetails,
                'fullDeptData' => $fullDeptData,
                'requestedText' => $requestedText,
                'saFiles' => $saFiles,
            ]);
        }
        catch(Exception $ex)
        {
            return back()->with('warning', $ex->getMessage());
        }
    }

    public function saveAction(Request $req)
    {
        // dd($req->all());
        try
        {
            $username = session('username');
            $deptOfficer = dept_officers_master::where('username', $username)->where('is_active', 1)->first();

            $clerk = dept_officers_master::where('role',5)->where('is_active',1)->first();
            $stateInfoComissioner = dept_officers_master::where('role',6)->where('is_active',1)->first();

            $saRequest = json_decode($req->saSubmitRequestDetails,true);

            $saSubmitReq = sa_submit_request::with('state', 'village', 'district')->find($saRequest['id']);
            if(is_null($saSubmitReq))
                throw new exception('Record Does Not Exists');

            $actionDescription = Actions::where('id', $req->selectedAction)->first();

            $validActions = [19, 20, 21, 22, 23];   // added action foe adding to procedings
            $statusId = null;

            if (!in_array($actionDescription->id, $validActions))
                throw new Exception('Wrong action selected');

            $saActionDetails = sa_action_details::where('reg_no', $saRequest['sa_reg_no'])->latest()->first();

            if (!$saActionDetails)
                throw new Exception('Cannot find relevant data. (Second Appeal Action Details)');

            $dataToInsert = array_intersect_key($saActionDetails->toArray(), array_flip($saActionDetails->getFillable()));

            sa_application_history::create([
                'sa_reg_no' => $saRequest['sa_reg_no'],
                'changes_id' => $req->selectedAction,
                'sa_action_details_id' => $saActionDetails->id,
                'history_field' => json_encode($dataToInsert),
                'current_dept_id' => $saSubmitReq->dept_id,
            ]);

            if ($actionDescription->id == 19)
            {
                $statusId = 15;
                SA_Reply_To_Applicant::create([
                    'sa_reg_no' => $saRequest['sa_reg_no'],
                    'reason' => $req->doc_req_reason,
                    'meta_data' => json_encode($req->doc_req_reason),
                ]);
            }

            if ($actionDescription->id == 20)
            {
                $statusId = 12;

                $msg = "Dear ".$saSubmitReq->name.", your Second Appeal is under process. Check website for updates - Dept. of Information and Publicity";
                $this->_sendSms($saSubmitReq->mobile_no,$msg);
            }

            if($actionDescription->id == 21)
            {
                $statusId = 13;
                $noticePath = session('noticePath');

                $pio = dept_officers_master::where('id',$req->pio_user)->where('is_active',1)->first();
                $faa = dept_officers_master::where('id',$req->faa_user)->where('is_active',1)->first();

                SA_Notice::create([
                    'file_path' => $noticePath,
                    'file_name' => basename($noticePath),
                    'status' => 'sent',
                    'sent_to' => json_encode([$req->pio_user]),
                    'created_by' => $deptOfficer->id,
                    'sa_reg_no' => $saSubmitReq->sa_reg_no,
                ]);

                $msg = "Dear ".$saSubmitReq->name.", a notice has been sent for your request. Check the website for details - Dept. of Information and Publicity";
                $this->_sendSms($saSubmitReq->mobile_no,$msg);

                $msg = "Notice sent for a Second Appeal. Please log in to review and act - Dept. of Information and Publicity";
                $this->_sendSms($pio->mobile_no, $msg);
                // $this->_sendSms($faa->mobile_no);
            }

            if($actionDescription->id == 23)
            {
                $statusId = 16;

                $folderPath = 'public/Second_Appeal_Documents/Disposed/' . $saSubmitReq->applied_dept;
                if (!Storage::exists($folderPath)) {
                    Storage::makeDirectory($folderPath);
                }

                if ($req->hasFile('judgement_doc'))
                {
                    $file = $req->file('judgement_doc');
                    $fileNameSplit = explode('/', $saSubmitReq->sa_reg_no);

                    $fileName = $fileNameSplit[3].'_'.date('dmyHi').'_DISPOSE.'.$file->getClientOriginalExtension();

                    $path = Storage::putFileAs(
                        $folderPath, $file, $fileName
                    );
                }
                if ($req->hasFile('judgement_s1'))
                {
                    $file1 = $req->file('judgement_s1');
                    $fileNameSplit = explode('/', $saSubmitReq->sa_reg_no);

                    $fileName1 = $fileNameSplit[3].'_'.date('dmyHi').'_DISPOSE_1.'.$file1->getClientOriginalExtension();

                    $path1 = Storage::putFileAs(
                        $folderPath, $file1, $fileName1
                    );
                }
                if ($req->hasFile('judgement_s2'))
                {
                    $file2 = $req->file('judgement_s2');
                    $fileNameSplit = explode('/', $saSubmitReq->sa_reg_no);

                    $fileName2 = $fileNameSplit[3].'_'.date('dmyHi').'_DISPOSE_2.'.$file2->getClientOriginalExtension();

                    $path2 = Storage::putFileAs(
                        $folderPath, $file2, $fileName2
                    );
                }

                saJudgement::create([
                    'sa_reg_no' => $saSubmitReq->sa_reg_no,
                    'disp_reason' => $req->judgement_text,
                    'disp_file_name' => $fileName,
                    'disp_file_original_name' => $file->getClientOriginalName(),

                    'disp_file2_name' => $fileName1,
                    'disp_file2_path' => $path1,
                    'disp_file2_original_name' => $file1->getClientOriginalName(),
                    'disp_file2_text' => $req->judgement_text_s1,

                    'disp_file3_name' => $fileName2,
                    'disp_file3_path' => $path2,
                    'disp_file3_original_name' => $file2->getClientOriginalName(),
                    'disp_file3_text' => $req->judgement_text_s2,

                    'meta_data' => null,
                ]);

            }

            sa_action_details::where('reg_no', $saRequest['sa_reg_no'])
            ->update([
                'status_id' => $statusId,
                'assigned_off1' => $clerk->id,
                'assigned_off2' => $stateInfoComissioner->id,
                'updated_by' => $deptOfficer->id,
                'ip_address' => $req->ip(),
                'action_id' => $req->selectedAction,
            ]);

            return redirect('/RTIMIS/dashboard/{new_SA}')->with('success', 'Change Request has been submitted');
        }
        catch(exception $ex)
        {
            return back()->with('warning', $ex->getMessage());
        }
    }


    public function getNotices()
    {
        $username = session('username');
        $deptOfficer = dept_officers_master::where('username', $username)
        ->where('is_active', 1)->first();

        $getNotice = sa_submit_request::from('sa_submit_requests as ssr')
        ->join('sa_action_details as sad', 'ssr.sa_reg_no', '=', 'sad.reg_no')
        ->join('sa_notice as sn', 'ssr.sa_reg_no', '=', 'sn.sa_reg_no')
        ->join('application_status as as2', 'sad.status_id' ,'=', 'as2.id')
        ->whereJsonContains('sn.sent_to', (string) $deptOfficer->id)
        ->select('ssr.name','ssr.reg_no', 'ssr.fa_reg_no', 'ssr.sa_reg_no', 'sn.file_path', 'sad.created_at', 'sn.file_path', 'ssr.created_at as efiling_date', 'as2.status');
        // ->get();

        // dd($getNotice);

        $role = $deptOfficer->role;
        $requestLines = $getNotice->paginate(10);
        // dd($requestLines);

        return view('ShowNotices', compact('requestLines','role'));
    }

    /**
    * Uploads a document and saves its details to the database.
    *
    * @param \Illuminate\Http\UploadedFile $file
    * @param string $folderPath
    * @param string $saRegNo
    * @param int $documentCategory
    * @param string|null $supportingText
    */
    private function uploadDocument($deptDetails, $file, $folderPath, $saRegNo, $documentCategory, $supportingText = null)
    {
        $originalFileName = $file->getClientOriginalName();
        $currentId = sa_uploaded_documents::where('sa_reg_no', $saRegNo)->count()+1;

        $dateString = now()->format('Ymd');
        $fileName = substr($deptDetails->dept_id, 0, 2) . '_' . $dateString . '_' . $currentId . '.' . $file->getClientOriginalExtension();

        Storage::putFileAs($folderPath, $file, $fileName);

        $metaData = [];
        if ($supportingText) {
            $metaData['supportingText'] = $supportingText;
        }

        if($documentCategory == 1)
        {
            $metaData = session('rtiRequestText');
        }
        else if($documentCategory == 2)
        {
            $metaData = session('faaRequestText');
        }

        sa_uploaded_documents::create([
            'sa_reg_no' => $saRegNo,
            'document_category' => $documentCategory,
            'doc_name' => $fileName,
            'doc_path' => "$folderPath/$fileName",
            'doc_original_name' => $originalFileName,
            'meta_data' => json_encode($metaData),
        ]);
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

}