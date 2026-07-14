<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\DepartmentView;
use App\Models\DepartmentNew;
use App\Models\dept_officers_master;
use App\Models\fa_application_status;
use App\Models\fa_submit_request;
use App\Models\fa_uploaded_documents;
use App\Models\fa_document_category;
use App\Models\State;
use App\Models\SubmitRequest;
use App\Models\FaActionDetails;
use App\Models\FaUploadDocuments;
use App\Models\FaaDepartmentMapping;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\GroundAppeal;
use App\Models\Rti_Dept_Id;
use Illuminate\Support\Facades\Artisan;
use App\Models\District;
use App\Models\Village;
use App\Models\Role_Action;
use App\Models\Role;
use App\Models\pioResponse;
use App\Models\Actions;
use App\Models\FaActions;
use App\Models\Taluka;
use Illuminate\Support\Facades\Session as FacadesSession;
use Exception;
use App\Rules\CaptchaValid;
use App\Models\action_details;

class FirstAppealController extends Controller
{
    public function index(){
        $data['deptData'] = DepartmentNew::where(['office_code' =>0, 'suboffice_code' => 0, 'office' => 'NA', 'suboffice' => 'NA'])->get();

        $data['stateData']= State::where('id', 6)->get();

        $data['groundAppealData']= GroundAppeal::get();

        return view('firstAppeal.firstAppealCommon');
    }

    public function firstAppealDetailForm(Request $request)
    {
        // dd('in');
        // dump($request->all());
        $this->validate($request, [
            'reg_no'  => ['required', 'string', 'regex:/\/NR\//'],
            'emailId' => ['required', 'email'],
        ], [
            'reg_no.regex'   => 'Invalid Registration No.',
            'emailId.required' => 'The email id field is required.',
            'emailId.email'    => 'Enter Valid Email ID',
        ]);
        try
        {
            $data['deptData'] = DepartmentNew::where(['office_code' =>0, 'suboffice_code' => 0, 'office' => 'NA', 'suboffice' => 'NA'])->get();

           $data['stateData']= State::where('id', 6)->get();

            $data['groundAppealData']= GroundAppeal::get();

            $data['submitRequest'] = SubmitRequest::where('reg_no', $request->reg_no)->first();
            // dd($data['submitRequest']);

            if(isset($data['firstAppealRequest']))
            {
                throw new Exception("First Appeal is already Submitted for this Registration Number");
            }

            $data['firstAppealRequest'] = fa_submit_request::where('reg_no', $request->reg_no)->first();

            if(isset($data['firstAppealRequest']))
            {
                throw new Exception("First Appeal is already Submitted for this Registration Number");
            }

            if(isset($data['submitRequest'])){
                $regNo = $request->reg_no;
                $submitRequestid = $data['submitRequest']->id;
                // return view('firstAppeal.firstAppealForm',$data);
                return redirect()->route('first-appeal-create',$submitRequestid);
            }
            else{
                throw new Exception("Please check Registration Number");
            }
        }
        catch(Exception $ex)
        {
            return redirect()->route('first-appeal-detail')->with('warning', $ex->getMessage());
        }
    }

    public function firstAppealDestroySession(Request $request)
    {
        $request->session()->flush();
        return back();
    }

    public function firstAppealCreate(Request $request,$id)
    {
        $submitReqData = SubmitRequest::with('actionDetails')->where('id',$id)->first();

        $statusId = $submitReqData->actionDetails->first()->status_id;

        if($statusId != 5)
        {
            $nowDate = \Carbon\Carbon::now();
            $createdDate = \Carbon\Carbon::parse($submitReqData['created_at'])->startOfDay();

            $diff = $createdDate->diffInDays($nowDate);

            if($diff < 30)
                return redirect()->back()->with('warning', 'First Appeal can be filed only after 30 days of e-filing of initial RTI Application or Disposal of RTI Request');
        }
        // Query the DepartmentNew table once to retrieve all the required data
        $deptData = DepartmentNew::where(['dept_code' => $submitReqData['applied_dept']])
                ->where('office_code', 0)
                ->where('suboffice_code',0)
                ->orderBy('office_code')
                ->orderBy('suboffice_code')
                ->first();

        $officeData =  DepartmentNew::where(['dept_code' => $submitReqData['applied_dept']])
                ->where('office_code', $submitReqData['applied_subdept'])
                ->where('suboffice_code',0)
                ->orderBy('office_code')
                ->orderBy('suboffice_code')
                ->first();

        $subOfficeData =  DepartmentNew::where(['dept_code' => $submitReqData['applied_dept']])
                ->where('office_code', $submitReqData['applied_subdept'])
                ->where('suboffice_code',$submitReqData['applied_office'])
                ->orderBy('office_code')
                ->orderBy('suboffice_code')
                ->first();

        $data = [];
        $data['deptData'] = [[$submitReqData['applied_dept'] => $deptData, $submitReqData['applied_subdept'] => $officeData, $submitReqData['applied_office'] => $subOfficeData]];

        if ($submitReqData->state == 0)
        {
            $data['stateData'] = (object)[
                'id' => 0,
                'name' => 'Others'
            ];
        }
        else
        {
                $data['stateData'] = State::where('id', $submitReqData->state)->first();
        }
        $data['district'] = District::where('id',$submitReqData->district)->first();
        $data['taluka'] = Taluka::where('id',$submitReqData->taluka)->first();
        $data['village'] = Village::where('id',$submitReqData->village)->first();
        $data['groundAppealData']= GroundAppeal::get();

        $data['submitRequest'] = $submitReqData;
        $filePath = $data['submitRequest']['document_path'];
        $data['filePath'] = Storage::url($filePath);

        // dd($data['stateData']->id);
        // $data['pioData'] = dept_officers_master::where('dept_code', 04)
        // ->where('office_code',00)
        // ->where('suboffice_code',00)
        // ->where('is_active',1)
        // ->where('role',2)
        // // where('dept_code', $submitReqData->applied_dept)
        // // ->where('office_code',$submitReqData->applied_subdept)
        // // ->where('suboffice_code',$submitReqData->applied_office)
        // ->first();

        $data['pioDetails'] = action_details::select(
                'dept_officers_master.*'
            )
            ->join(
                'dept_officers_master',
                'action_details.assigned_off2',
                '=',
                'dept_officers_master.id'
            )
            ->where('reg_no', $data['submitRequest']->reg_no)
            ->first();

        $data['deptDropdownKeys'] = [$submitReqData['applied_dept'],$submitReqData['applied_subdept'],$submitReqData['applied_office']];
        $data['deptDropdownValues'] = [$deptData->department,$officeData->department,$subOfficeData->department];

        $data['firstAppealRequest'] = fa_submit_request::where('reg_no', $data['submitRequest']->reg_no)->first();
        return view('firstAppeal.firstAppealForm', compact('data'));

    }

    public function firstAppealSave(Request $request)
    {
        $formData = $request->all();

        // 1. Honeypot Check
        if ($request->filled('middle_name')) {
            // Log the bot attempt if you want
            \Log::info("Bot caught by honeypot: " . $request->ip());

            // Return success so the bot thinks it won, but don't save anything
            return back()->with('success', 'Form submitted successfully!');
        }
        $request->validate([
            'request_text' => 'required',
            'reg_no' => 'required',
            'pioname' => 'required',
            // Add the max constraint here
            'pioaddr' => 'required|string|max:50',
            'pio_resp_dropdown' => 'required',
            'captcha' => ['required', new CaptchaValid],
        ], [
            'pioaddr.max' => 'The PIO address must not exceed 50 characters.',
            'captcha.required' => 'Please enter the security code.',
        ]);

        DB::beginTransaction();
        try
        {
            $submitRequestObj = new SubmitRequest;
            $deptCode = $submitRequestObj->createDeptCode($formData['applied_dept'],$formData['applied_subdept'],$formData['applied_office']);
            $deptDetails = Rti_Dept_Id::where('dept_id', $deptCode)->first();

            $date = Carbon::now();
            $dateString = $date->format('dmy');

            $faRegNo = null;
            $fa_submit_object = new fa_submit_request();

            $nextSequenceValue = DB::select("SELECT NEXTVAL('main_fa_id')")[0]->nextval;
            $faRegNo = $fa_submit_object->genRegNumber($deptDetails->pubauthcode,date('m').date('y'),$nextSequenceValue);

            $fa_submit_object->create([
                'applied_dept' => $request->applied_dept,
                'applied_subdept' => $request->applied_subdept,
                'applied_office' => $request->applied_office,
                'dept_id' => isset($deptDetails->dept_id) ? $deptDetails->dept_id: null,
                'reg_no' => $request->reg_no,
                'fa_reg_no' => $faRegNo,
                'name' => isset($request->name) ? $request->name : null,
                'gender' => isset($request->gender) ? $request->gender : null,
                'address_line1' => isset($request->address_line1) ? $request->address_line1 : null,
                'address_line2' => isset($request->address_line2) ? $request->address_line2 : null,
                'address_line3' => isset($request->address_line3) ? $request->address_line3 : null,
                'pincode' => isset($request->pincode) ? $request->pincode : null,
                'country' => isset($request->country) ? $request->country : 1,
                'state' => isset($request->state) ? $request->state : null,
                'district' => isset($request->district) ? $request->district : null,
                'taluka' => isset($request->taluka) ? $request->taluka : null,
                'village' => isset($request->village) ? $request->village : null,
                'phone_no' => isset($request->phone_no) ? $request->phone_no : null,
                'mobile_no' => isset($request->mobile_no) ? $request->mobile_no : null,
                'email_id' => isset($request->email_id) ? $request->email_id : null,
                'pioname' => isset($request->pioname) ? $request->pioname : null,
                'pioaddr' => isset($request->pioaddr) ? $request->pioaddr : null,
                'pioresponse' => isset($request->pio_resp_dropdown) ? $request->pio_resp_dropdown : null,
                'requested_note' => isset($request->pioaddr) ? $request->request_text : null,
            ]);

            if ($request->hasFile('pio_reply_document') || $request->hasFile('document'))
            {

                $lastInsertedId = fa_uploaded_documents::orderBy('id', 'desc')->first();
                $currentId = is_null($lastInsertedId) ? 1 : $lastInsertedId->id + 1;
                // Define folder path based on department
                $folderPath = 'public/First_Appeal_Documents/' . substr($deptDetails->dept_id, 0, 2);
                if (!Storage::exists($folderPath)) {
                    Storage::makeDirectory($folderPath);
                }
            }
            if ($request->hasFile('pio_reply_document'))
            {
                $file = $request->file('pio_reply_document');
                $fileName = substr($deptDetails->dept_id, 0, 2) .'_'. $dateString .'_'.$currentId.'.'. $file->getClientOriginalExtension();
                $originalFileName = $file->getClientOriginalName();
                $path = Storage::putFileAs(
                    $folderPath, $file, $fileName
                );
                $formData['pio_reply_document'] = $folderPath.'/'.$fileName;

                $documentData = new fa_uploaded_documents();
                $documentData->reg_no = $request->reg_no;
                $documentData->document_category = 7;
                $documentData->document_name = $fileName;
                $documentData->document_path  = $folderPath.'/'.$fileName;

                $documentData->document_upload_date = Carbon::now();
                $documentData->document_name_original = $originalFileName;
                $documentData->save();
                $currentId++;
            }

            if ($request->hasFile('document'))
            {
                $file2 = $request->file('document');

                $newFileName = substr($deptDetails->dept_id, 0, 2) .'_'. $dateString .'_'.$currentId.'.'. $file2->getClientOriginalExtension();
                $originalnewFileName = $file2->getClientOriginalName();

                $path = Storage::putFileAs(
                    $folderPath, $file2, $newFileName
                );

                $pioReplyDocument = new fa_uploaded_documents();
                $pioReplyDocument->reg_no = $request->reg_no;
                $pioReplyDocument->document_category = 2;
                $pioReplyDocument->document_name = $newFileName;
                $pioReplyDocument->document_path  = $folderPath.'/'.$newFileName;
                $pioReplyDocument->document_upload_date = Carbon::now();
                $pioReplyDocument->document_name_original = $originalnewFileName;
                $pioReplyDocument->save();

                $formData['document'] = $folderPath.'/'.$newFileName;
                $currentId++;

            }
            else
            {
                $formData['document'] = null;
            }

            $faaDeptDetails = FaaDepartmentMapping::where('dept_code',$request->applied_dept)->where('office_code',$request->applied_subdept)->where('suboffice_code',$request->applied_office)->first();

            $nodelData = dept_officers_master::where('dept_id', substr($deptCode,0,2).'0000')->where('role',1)->first(); // Nodal Officer

            $faData = dept_officers_master::where('dept_id', $faaDeptDetails->fadept_id)->where('role',4)->first(); // First Appellate Authority

            $faApplicationStatus = fa_application_status::create([
                'fa_reg_no' => $faRegNo,
                'reg_no' => $request->reg_no,
                'status_id' => 1,
                'action_id' => 16,
                'assigned_off1' => $nodelData ? $nodelData->id : null,
                'assigned_off2' => $faData ? $faData->id : null,
                'updated_by' => 8
            ]);

            $currentDetails = $faApplicationStatus->toArray();
            $actionDetailsFillable = $faApplicationStatus->getFillable();
            $dataToInsert = array_intersect_key($currentDetails, array_flip($actionDetailsFillable));

            $updateHistoryDetails = [
                'changes' => 'FIRST APPEAL NEW REQUEST SUBMITTED',
                'fa_reg_no' => $faRegNo,
                'fa_application_id' => $faApplicationStatus->id,
                'history_field' => json_encode($dataToInsert),
            ];

            $saveActionDetailsId = FaActionDetails::create($updateHistoryDetails)->id;

            DB::commit();
            $submittedDetails = fa_submit_request::where('fa_reg_no', $faRegNo)->first();

            $departmentInfo = DepartmentNew::where(['dept_code' => $submittedDetails->applied_dept, 'office_code' =>   $submittedDetails->applied_subdept, 'suboffice_code' => $submittedDetails->applied_office, 'office' => 'NA', 'suboffice' => 'NA'])->first();

            // sms for FAA
            $var1 ="Sir/Madam";
            $var2 = "First Appeal Request";
            $var3 = "been received and assigned against your FAA ID";

            $faaMsg = "Dear ".$var1.", RTI ".$var2." registration number ".$faRegNo." has ".$var3." - Dept. of Information and Publicity";
            $this->_sendSms($faData->mobile_no,$faaMsg);

            // sms for applicant
            $msg = "Your "."RTI First Appeal"." has been filed vide registration number ".$faRegNo." Dept. of Information and Publicity";
            $this->_sendSms(FacadesSession::get('mobile_no'),$msg);

            return view('FA_Application', ['submittedDetails' => $submittedDetails, 'departmentInfo' => $departmentInfo]);
        }
        catch (\Illuminate\Validation\ValidationException $e)
        {
            return redirect()->back()->withErrors($e->validator->getMessageBag())->withInput();
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return redirect()->route('first-appeal-detail')->with('error', $e->getMessage());
        }
    }

    public function getCommentList()
    {
        $username = session('username');
        $deptOfficer = dept_officers_master::where('username', $username)
        ->where('is_active', 1)->first();
        // dd($deptOfficer->id);

        // Base query, common for both cases
        $query = fa_submit_request::from('fa_submit_requests as fsr')
        ->join('fa_application_statuses as fas', 'fsr.fa_reg_no', '=', 'fas.fa_reg_no')
        ->join('fa_actions as fa', 'fsr.fa_reg_no', '=', 'fa.fa_reg_no')
        ->join('application_status', 'fas.status_id', '=', 'application_status.id')
        ->select('fsr.id', 'fsr.reg_no', 'fsr.fa_reg_no', 'fas.assigned_off1', 'fas.assigned_off2', 'fsr.created_at', 'application_status.status')
        ->whereNotNull('fa.faa_comments');

        if ($deptOfficer->role == 4)
        {
            $firstAppeal = $query->where('fas.assigned_off2', $deptOfficer->id);
        }
        else
        {
            $firstAppeal = $query
            ->join('action_details as ad', 'fsr.reg_no', '=', 'ad.reg_no')
            ->select('fsr.id', 'fsr.reg_no', 'fsr.name', 'fsr.fa_reg_no', 'fas.assigned_off1', 'fas.assigned_off2', 'fsr.created_at', 'ad.reg_no', 'ad.assigned_off2', 'application_status.status')
            ->where('ad.assigned_off2', $deptOfficer->id);
        }

        $role = $deptOfficer->role;
        $requestLines = $firstAppeal->paginate(10);
        // dd($requestLines);

        return view('CommentLines', compact('requestLines','role'));
    }

    public function firstAppealCommentDetails($id)
    {
        $username = session('username');
        $userDetails['name'] = $username;

        $deptOfficer = dept_officers_master::where('username', $username)
        ->where('is_active', 1)->first();

        $faDetails = fa_submit_request::from('fa_submit_requests as fsr')
        ->join('fa_application_statuses as fas', 'fsr.fa_reg_no', '=', 'fas.fa_reg_no')
        ->join('submit_requests as sr', 'fsr.reg_no', '=', 'sr.reg_no')
        ->leftJoin('states', 'fsr.state', '=', 'states.id')
        ->select('fsr.*', 'fas.status_id', 'states.name as stateName','sr.reg_no','sr.created_at as submitted_date','fsr.requested_note','sr.document_path','sr.document_name_original','sr.request_text as sr_req_note','fsr.requested_note as fa_req_note','fsr.pioresponse','sr.village','sr.district','sr.mobile_no','sr.phone_no')
        ->where('fsr.id', $id)->first();

        // dd($faDetails, $faDetails->fa_reg_no);
        $commentDetails = FaActions::where('fa_reg_no',$faDetails->fa_reg_no)->whereNotNull('faa_comments')->orderBy('id')->first();

        // dd($commentDetails);

        $villageFetch = Village::where('id',$faDetails->village)->first();
        $districtFetch = District::where('id',$faDetails->district)->first();
        $pioResonse = pioResponse::where('id',$faDetails->pioresponse)->first();
        $uploadedDocs = fa_uploaded_documents::where('reg_no',$faDetails['reg_no'])->get();
        $pioReplyDoc = $uploadedDocs->where('document_category','7')->first();
        $supportingDoc = $uploadedDocs->where('document_category','4')->first();
        $submitReq = SubmitRequest::where('reg_no', $faDetails['reg_no'])->first();

        $departmentDetails1 = DepartmentNew::where('dept_code',$submitReq->applied_dept)->where('office_code', 0)->where('suboffice_code',0)->first();

        $departmentDetails2 = DepartmentNew::where('dept_code',$submitReq->applied_dept)->where('office_code', $submitReq->applied_subdept)->where('suboffice_code',0)->first();

        $departmentDetails3 = DepartmentNew::where('dept_code',$submitReq->applied_dept)->where('office_code', $submitReq->applied_subdept)->where('suboffice_code',$submitReq->applied_office)->first();

        $departmentDetails = [
            'dept' => $departmentDetails1->department,
            'office' => $departmentDetails2->department,
            'suboffice' => $departmentDetails3->department,
        ];

        $actionIdtoUse = null;
        if($faDetails->status_id == 1)
        {
            $actionIdtoUse = [10,13,14];    // new request action ids
            // $actionIdtoUse = [11,12];
        }
        else
        {
            $actionIdtoUse = [11,12];   // in process action ids
            // $actionIdtoUse = [10,13,14];
        }
        $roleDetails = Role::where('id',$deptOfficer->role)->first();
        $userDetails['role_name'] = $roleDetails->name;
        $actionIds = Role_Action::where('role_id', $deptOfficer->role)->pluck('action_id');
        $actionsData = Actions::whereIn('id', $actionIdtoUse) ->whereIn('id', $actionIds)
        ->where('action_type', 'fa_request')->get();

        $uploadedDocs = [
            'suppDoc' => ($supportingDoc == null ) ? null : $supportingDoc,
            'pioReplyDoc' => ($pioReplyDoc == null ) ? null : $pioReplyDoc,
            'details' => $pioResonse,
            'village' => $villageFetch->name == null ? 'Other' : $villageFetch->name,
            'district' => $districtFetch->name == null ? 'Other' : $districtFetch->name,
        ];

        return view('firstAppeal.detailedComments', compact('faDetails', 'submitReq', 'departmentDetails', 'userDetails', 'actionsData','uploadedDocs', 'commentDetails'));
    }

    public function savePioComment(request $request)
    {
        // dd($request);
        try
        {
            $requestDetail = json_decode($request->get('requestDetail'));

            if(!$requestDetail->fa_reg_no)
                throw new exception('Error in Request Detail');

            $commentDetails = FaActions::where('fa_reg_no',$requestDetail->fa_reg_no)->first();
            if($commentDetails)
            {
                $commentDetails->update([
                    'spio_comments' => $request->spioComment,
                ]);
                $commentDetails->save();
            }

            return redirect('/getCommentLists')->with('success', 'SPIO / PIO Comment Successfully Sent');
        }
        catch(Exception $ex)
        {
            return redirect('/getCommentLists')->with('warning', $ex->getMessage());
        }

    }

    private function _sendSms($mobile,$msg)
    {
        Artisan::call('app:send-sms', [
            'mobile' => $mobile,
            'message' => $msg,
        ]);

        $output = Artisan::output();

        return response()->json(['Status' => 200, 'message' => "Sms Sent Successfully"]);
    }

}
