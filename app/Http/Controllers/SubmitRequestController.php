<?php

namespace App\Http\Controllers;

use App\Models\action_details;
use App\Models\Actions;
use App\Models\Additional_Payment_details;
use App\Models\Payment_Details;
use App\Models\application_history;
use App\Models\Rti_Dept_Id;
use Illuminate\Http\Request;
use App\Models\SubmitRequest;
use App\Models\rti_received_application_status;
use App\Models\rti_application_actions;
use App\Models\DepartmentNew;
use App\Models\dept_officers_master;
use App\Models\fa_application_status;
use App\Models\fa_submit_request;
use App\Models\sa_submit_request;
use App\Models\fa_uploaded_documents;
use App\Models\FaActionDetails;
use App\Models\FaActions;
use App\Models\Req_dispose_of;
use App\Models\pioResponse;
use App\Models\Village;
use App\Models\District;
use App\Models\Req_to_applicant;
use App\Models\Role;
use App\Models\Role_Action;
use App\Models\rtiStatusFaReq;
use App\Models\rtiStatusSaReq;
use App\Models\TransferToAnotherDepartment;
use App\Models\ForwardToSubDepartment;
use App\Models\FaaDepartmentMapping;
use App\Models\RtiApplicantRegdata;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\AppReqStatus;
use Illuminate\Support\Facades\Session;

class SubmitRequestController extends Controller
{
    public function index($req_type = null)
    {
        try
        {
            $username = session('username');
            $sessionRole = session('role');
            $userDetails['name'] = $username;

            $deptOfficer = dept_officers_master::where('username', $username)
            ->where('is_active', 1)->first();

            // $deptOfficers = dept_officers_master::where('dept_id',$deptOfficer->dept_id)
            // ->where('dept_code',$deptOfficer->dept_code)->first();
            if($deptOfficer->role != 4)
            {
                $d1 = DepartmentNew::where('dept_code',substr($deptOfficer->dept_id,0,2))->where('office_code',substr($deptOfficer->dept_id,2,2))->where('suboffice_code',substr($deptOfficer->dept_id,4,2))->first();
                if($d1 != null)
                    Session::put('officeName', $d1->department);
                else
                    Session::put('officeName', null);
            }
            else
                Session::put('officeName', null);

            if($deptOfficer->role == 4)
            {
                $faaDeptInfo = FaaDepartmentMapping::where('dept_id',$deptOfficer->dept_id)->first();

                if($faaDeptInfo != null)
                {
                    $faaDept = DepartmentNew::where('dept_code',substr($faaDeptInfo->fadept_id,0,2))->where('office_code',substr($faaDeptInfo->fadept_id,2,2))->where('suboffice_code',substr($faaDeptInfo->fadept_id,4,2))->first();
                    Session::put('faaDeptName', $faaDept->department);
                }
                else
                {
                   $d1 = DepartmentNew::where('dept_code',substr($deptOfficer->dept_id,0,2))->where('office_code',substr($deptOfficer->dept_id,2,2))->where('suboffice_code',substr($deptOfficer->dept_id,4,2))->first();
                   Session::put('faaDeptName', $d1->department);
                }
            }

            $roleDetails = Role::where('id',$deptOfficer->role)->first();
            $userDetails['role_name'] = $roleDetails->name;
            $submitRequests = null;

            Session::put('role_name',$roleDetails->name);
            if($sessionRole == 4 && $req_type === null)
            {
                $req_type = '{new_FA}';
            }

            if($req_type === '{new_req}' || $req_type === '{in_process}' || $req_type === '{closed}' || $req_type === '{transForward}' || $req_type === null)
            {
                $submitRequests = SubmitRequest::join('action_details', 'submit_requests.reg_no', '=', 'action_details.reg_no')
                ->join('application_status', 'action_details.status_id', '=', 'application_status.id')
                ->join('dept_officers_master','action_details.assigned_off2', '=' , 'dept_officers_master.id');

                if($req_type == '{transForward}')
                {
                    $submitRequests->join(
                        DB::raw('(SELECT DISTINCT ON (reg_no) reg_no, changes FROM application_history ORDER BY reg_no, updated_at DESC) AS ah'),
                        'submit_requests.reg_no', '=', 'ah.reg_no'
                    );
                    // $submitRequests ->join('application_history','submit_requests.reg_no', '=', 'application_history.reg_no');
                }

                $submitRequests->where('submit_requests.payment_details', 'Success');
                if($req_type == '{transForward}')
                {
                    $submitRequests->select('submit_requests.*','application_status.status','action_details.assigned_off1','action_details.assigned_off2','dept_officers_master.username','action_details.updated_at as action_updated','ah.changes');
                }
                else
                {
                    $submitRequests->select('submit_requests.*','application_status.status','action_details.assigned_off1','action_details.assigned_off2','dept_officers_master.username','action_details.updated_at as action_updated')
                    ->distinct();
                }

                $submitRequests->orderby('updated_at', 'desc');

                if ($req_type === '{new_req}' || $req_type === null)
                {
                    $req_type = '{new_req}';
                    $submitRequests->whereIn('action_details.status_id', array(1,6,7,8))
                    ->where(function ($query) use ($deptOfficer) {
                        $query->where('action_details.assigned_off1', $deptOfficer->id)
                            ->orWhere('action_details.assigned_off2', $deptOfficer->id);
                    });
                }
                elseif ($req_type === '{in_process}')
                {
                    $submitRequests->whereIn('action_details.status_id', array(2,9,10))
                    ->where(function ($query) use ($deptOfficer) {
                        $query->where('action_details.assigned_off1', $deptOfficer->id)
                            ->orWhere('action_details.assigned_off2', $deptOfficer->id);
                    });
                }
                elseif($req_type === '{closed}')
                {
                    $submitRequests->whereIn('action_details.status_id', array(3,4,5))
                    ->where(function ($query) use ($deptOfficer) {
                        $query->where('action_details.assigned_off1', $deptOfficer->id)
                            ->orWhere('action_details.assigned_off2', $deptOfficer->id);
                    });
                }
                elseif($req_type === '{transForward}')
                {
                    if($deptOfficer->role == 1)
                        $submitRequests->where('action_details.assigned_off1', $deptOfficer->id);

                    $submitRequests->whereIn('action_details.status_id', array(6,7,8))
                    ->where(function($query) use ($deptOfficer) {
                        $query->where('action_details.updated_by', $deptOfficer->id)
                              ->orWhere('action_details.assigned_off2', $deptOfficer->id);
                    })
                    ->whereIn('ah.changes', array ( 'TRANSFER REQUEST TO OTHER PUBLIC AUTHORITY', 'FORWARD REQUEST TO PIO','FORWARD REQUEST TO CONCERNED PIO','PARTIAL TRANSFER OF SUBMITTED APPLICATION','FORWARD REQUEST TO CONCERNED APIO'))
                    ->groupBy('submit_requests.id','application_status.status','action_details.assigned_off1','action_details.assigned_off2','dept_officers_master.username','action_details.updated_at','ah.changes');
                }
                $query = $submitRequests; // still builder

                // Print query and bindings before executing paginate
                /*$fullSql = vsprintf(
                    str_replace('?', '%s', $query->toSql()),
                    $query->getBindings() // just raw bindings, no quotes
                );*/

                //dd($fullSql); // prints the full query with values
                $submitRequests = $submitRequests->paginate(10);



                return view('dashboardCommon', [
                    'req_type' => $req_type,
                    'submitRequests' => $submitRequests,
                    'userDetails' => $userDetails,
                    'sessionRole' => $sessionRole,
                ]);

            }
            else if($req_type === '{new_FA}' || $req_type === '{in_process_FA}' || $req_type === '{closed_FA}')
            {
                $userDetails['role_name'] = $roleDetails->name;

                // dd($userDetails,$deptOfficer);
                $firstAppealData = fa_submit_request::from('fa_submit_requests as fsr')
                ->join('fa_application_statuses as fas', 'fsr.fa_reg_no', '=', 'fas.fa_reg_no')
                ->join('application_status as as2', 'fas.status_id', '=', 'as2.id')
                ->join('dept_officers_master as dom','fas.assigned_off2', '=' , 'dom.id')
                ->select('fsr.id','fsr.name','fsr.reg_no','fsr.fa_reg_no','fsr.created_at','as2.status','dom.username','fas.updated_at')
                ->where(function ($query) use ($deptOfficer) {
                    $query->where('fas.assigned_off1', $deptOfficer->id)
                        ->orWhere('fas.assigned_off2', $deptOfficer->id);
                });

                if($req_type == '{new_FA}')
                {
                    $firstAppealData->where('fas.status_id', 1);
                }
                else if($req_type == '{in_process_FA}')
                {
                    $firstAppealData->where('fas.status_id', 2);
                }
                else if($req_type == '{closed_FA}')
                {
                    $firstAppealData->where('fas.status_id', 5);
                }
                $firstAppealData = $firstAppealData->orderBy('fas.id', 'desc')->paginate(10);

                //dd($firstAppealData);
                return view('dashboardFirstAppeal', compact('firstAppealData','req_type','userDetails'));
            }
            else if($req_type === '{new_SA}' || $req_type === '{in_process_SA}' || $req_type === '{closed_SA}')
            {
                // dd($userDetails,$deptOfficer);

                $secondAppealData = sa_submit_request::from('sa_submit_requests as ssr')
                ->join('sa_action_details as sad', 'ssr.sa_reg_no', '=', 'sad.reg_no')
                ->join('application_status as as2', 'sad.status_id', '=', 'as2.id')
                ->join('dept_officers_master as dom','sad.assigned_off2', '=' , 'dom.id')
                ->select('ssr.id','ssr.name','ssr.reg_no','ssr.sa_reg_no','ssr.created_at','as2.status','dom.username','sad.updated_at')
                ->where(function ($query) use ($deptOfficer) {
                    $query->where('sad.assigned_off1', $deptOfficer->id)
                        ->orWhere('sad.assigned_off2', $deptOfficer->id);
                });

                if($req_type == '{new_SA}')
                {
                    $secondAppealData->where('sad.status_id', 11);
                }
                else if($req_type == '{in_process_SA}')
                {
                    $secondAppealData->whereIn('sad.status_id', [12,13,14]);
                }
                else if($req_type == '{closed_SA}')
                {
                    $secondAppealData->whereIn('sad.status_id', [15,16]);
                }
                // $secondAppealData = $secondAppealData->orderBy('sad.id', 'desc')->get();
                $secondAppealData = $secondAppealData->orderBy('sad.id', 'desc')->paginate(10);

                return view('dashboardSecondAppeal', compact('secondAppealData','req_type','userDetails'));
            }
        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function dashboardInfo()
    {
        $test = "DepartmentName";
        return view('dashboardHeader', [
            'test' => $test
        ]);
    }

    public function submitRequestDetailedView($id)
    {
        $username = session('username');
        $userDetails['name'] = $username;

        $deptOfficer = dept_officers_master::where('username', $username)
        ->where('is_active', 1)->first();

        $roleDetails = Role::where('id',$deptOfficer->role)->first();
        $userDetails['role_name'] = $roleDetails->name;
        // dd($roleDetails);
        // $deptOfficer = $userDetails->deptOfficersMaster;

        $submitRequest = SubmitRequest::with('state', 'village', 'district', 'actionDetails')->find($id);

        $departmentDetails1 = DepartmentNew::where('dept_code',$submitRequest->applied_dept)->where('office_code', 0)->where('suboffice_code',0)->first();

        $departmentDetails2 = DepartmentNew::where('dept_code',$submitRequest->applied_dept)->where('office_code', $submitRequest->applied_subdept)->where('suboffice_code',0)->first();

        $departmentDetails3 = DepartmentNew::where('dept_code',$submitRequest->applied_dept)->where('office_code', $submitRequest->applied_subdept)->where('suboffice_code',$submitRequest->applied_office)->first();

        $filePath  = $submitRequest['document_path'];
        $filePath = Storage::url($filePath);

        $fullDeptData = [
            'department' => $departmentDetails1,
            'subDepartment' => $departmentDetails2,
            'officeDepartment' => $departmentDetails3,
        ];

        $statusId = $submitRequest->actionDetails->first()->status_id;
        $actionIds = Role_Action::where('role_id', $deptOfficer->role)->pluck('action_id');

        if($statusId == 1 || $statusId == 7 || $statusId == 8 || $statusId == 6)
        {
            // $actions = Actions::where('id', '<', 7)->orderBy('id')->get();
            $idsAction = [1,2,3,4,5,6,9,18];
            $actions = Actions::whereIn('id', $actionIds)->whereIn('id', $idsAction)->orderBy('id')->get();
        }
        else
        {
            $idsAction = [7,8];
            $actions = Actions::whereIn('id', $actionIds)->whereIn('id',$idsAction)->orderBy('id')->get();
        }
        $actionDetails = $actions;

        $allOfficers = dept_officers_master::where('is_active', 1)
        ->where('dept_code', $submitRequest->applied_dept)
        ->get();

        $nodalOfficers = $allOfficers->where('role', 1)->first();
        $pioOfficers = $allOfficers->where('role', 2)->first();
        $apioOfficers = $allOfficers->where('role', 3)->first();
        // dump($allOfficers, $nodalOfficers, $apioOfficers, $pioOfficers);

        $officersDetails = [
            'nodal' => $nodalOfficers,
            'pio' => $pioOfficers,
            'apio' => $apioOfficers
        ];

        $deptData = DepartmentNew::where(['office_code' =>0, 'suboffice_code' => 0, 'office' => 'NA', 'suboffice' => 'NA'])->get();

        $paymentDetails = Payment_Details::join('submit_requests as sr', 'payment__details.reg_no', '=', 'sr.reg_no')
        ->leftjoin('additional__payment_details as apd', 'sr.reg_no', '=', 'apd.reg_no')
        ->where('sr.reg_no', $submitRequest->reg_no)
        ->where('payment__details.status', 'Success')
        ->select('payment__details.total_amt','payment__details.echallan_no','payment__details.created_at')
        ->get();

        return view('detailedForm', [
            'deptDetails' => $departmentDetails1->department,
            'officeDetails' => $departmentDetails2->department,
            'subOfficeDetails' => $departmentDetails3->department,
            'submitRequest' => $submitRequest,
            'filePath' => $filePath,
            'actionDetails' => $actionDetails,
            'deptOfficer' => $deptOfficer,
            'userDetails' => $userDetails,
            'deptData' => $deptData,
            'officersDetails' => $officersDetails,
            'paymentDetails' => $paymentDetails,
            'fullDeptData' => $fullDeptData,
        ]);
    }

    public function closedRequestDetailedView($id)
    {

        $username = session('username');
        $userDetails['name'] = $username;

        $deptOfficer = dept_officers_master::where('username', $username)
        ->where('is_active', 1)->first();

        $roleDetails = Role::where('id',$deptOfficer->role)->first();
        $userDetails['role_name'] = $roleDetails->name;

        $submitRequest = SubmitRequest::with('state', 'village', 'district', 'actionDetails')->find($id);

        $departmentDetails1 = DepartmentNew::where('dept_code',$submitRequest->applied_dept)->where('office_code', 0)->where('suboffice_code',0)->first();

        $departmentDetails2 = DepartmentNew::where('dept_code',$submitRequest->applied_dept)->where('office_code', $submitRequest->applied_subdept)->where('suboffice_code',0)->first();

        $departmentDetails3 = DepartmentNew::where('dept_code',$submitRequest->applied_dept)->where('office_code', $submitRequest->applied_subdept)->where('suboffice_code',$submitRequest->applied_office)->first();

        $filePath  = $submitRequest['document_path'];
        $filePath = Storage::url($filePath);

        $statusId = $submitRequest->actionDetails->first()->status_id;

        $paymentDetails = Payment_Details::join('submit_requests as sr', 'payment__details.reg_no', '=', 'sr.reg_no')
        ->leftjoin('additional__payment_details as apd', 'sr.reg_no', '=', 'apd.reg_no')
        ->where('sr.reg_no', $submitRequest->reg_no)
        ->where('payment__details.status', 'Success')
        ->select('payment__details.total_amt','payment__details.echallan_no','payment__details.created_at')
        ->get();

        $reqestDisposeOf = Req_dispose_of::where('reg_no', $submitRequest->reg_no)->first();


        $officerFile = [];

        if($reqestDisposeOf!=null)
        {
            $documentPaths = [
            $reqestDisposeOf['document_path'],
            $reqestDisposeOf['document_path1'],
            $reqestDisposeOf['document_path2']
        ];

            foreach ($documentPaths as $documentPath) {
                if ($documentPath) {
                    $officerFile[] = Storage::url($documentPath);
                } else {
                    $officerFile[] = null;
                }
            }
        }

        $requestToApplicant = Req_to_applicant::where('reg_no',$submitRequest->reg_no)->first();

        // dd($submitRequest,$reqestDisposeOf);
        return view('closedRequestDetailedView', [
            'deptDetails' => $departmentDetails1->department,
            'officeDetails' => $departmentDetails2->department,
            'subOfficeDetails' => $departmentDetails3->department,
            'submitRequest' => $submitRequest,
            'filePath' => $filePath,
            'officerFile' => $officerFile,
            'userDetails' => $userDetails,
            'paymentDetails' => $paymentDetails,
            'reqestDisposeOf' => $reqestDisposeOf,
            'requestToApplicant' => $requestToApplicant,
        ]);
    }

    public function editOfficerReply(Request $req)
    {
        try
        {
            $submitRequestDetails = json_decode($req->get('submitRequestDetails'));
            $folderPath = 'public/Document_Reply/'.$submitRequestDetails->applied_dept;

            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
            }

            if ($req->hasFile('officerFile1'))
            {
                $file = $req->file('officerFile1');

                $fileNameSplit = explode('/', $submitRequestDetails->reg_no);
                $newFileName = $fileNameSplit[3] . '_' . date('dmyHi') . "_REPLY." . $file->getClientOriginalExtension();
                $path = Storage::putFileAs($folderPath, $file, $newFileName);

                $reqDetails = [
                    'document_name' => $newFileName,
                    'document_path' => $path,
                    'original_doc_name' => $file->getClientOriginalName(),
                ];

                Req_dispose_of::where('reg_no', $submitRequestDetails->reg_no)->update($reqDetails);
            }

            if ($req->hasFile('officerFile2'))
            {
                $file = $req->file('officerFile2');

                $fileNameSplit = explode('/', $submitRequestDetails->reg_no);
                $newFileName = $fileNameSplit[3] . '_' . date('dmyHi') . "_REPLY." . $file->getClientOriginalExtension();
                $path = Storage::putFileAs($folderPath, $file, $newFileName);

                $reqDetails = [
                    'document_name1' => $newFileName,
                    'document_path1' => $path,
                    'original_doc_name1' => $file->getClientOriginalName(),
                ];

                Req_dispose_of::where('reg_no', $submitRequestDetails->reg_no)->update($reqDetails);
            }

            if ($req->hasFile('officerFile3'))
            {
                $file = $req->file('officerFile3');

                $fileNameSplit = explode('/', $submitRequestDetails->reg_no);
                $newFileName = $fileNameSplit[3] . '_' . date('dmyHi') . "_REPLY." . $file->getClientOriginalExtension();
                $path = Storage::putFileAs($folderPath, $file, $newFileName);

                $reqDetails = [
                    'document_name2' => $newFileName,
                    'document_path2' => $path,
                    'original_doc_name2' => $file->getClientOriginalName(),
                ];

                Req_dispose_of::where('reg_no', $submitRequestDetails->reg_no)->update($reqDetails);
            }
            return redirect('/RTIMIS/dashboard')->with('success', 'Change Request has been submitted');
        }
        catch(Exception $ex)
        {
            return redirect('/RTIMIS/dashboard')->with('warning', $ex->getMessage());
        }
    }

    public function saveAction(Request $request)
    {
        $fetchedDetails = $request->all();
        // dump($fetchedDetails);

        if(isset($fetchedDetails['partial_departments']))
            $partialTransferCount = count($fetchedDetails['partial_departments']);
        else
            $partialTransferCount = null;

        // dd('testtttt');
        try
        {
            $currentuser = session('username');
            $deptOfficer = dept_officers_master::where('username', $currentuser)
            ->where('is_active', 1)->first();

            // dump("logged in user dept_master_id->".$deptOfficer->id.'  name->'.$deptOfficer->name);

            $submitRequestDetails = json_decode($fetchedDetails['submitRequestDetails'], true) ?? null;
            $actionSelected = $fetchedDetails['selectedAction'] ?? null;
            $selectedMainDept = $fetchedDetails['applied_dept'] ?? null;
            $selectedSubDept = $fetchedDetails['applied_subdept'] ?? null;
            $selectedOfficeDept = $fetchedDetails['applied_office'] ?? null;
            $reqDisposeReason = $fetchedDetails['req_dispose_reason'] ?? null;
            $documentReqReason = $fetchedDetails['doc_req_reason'] ?? null;
            $modeInformationSupply = $fetchedDetails['mode_info_suppy'] ?? null;
            $additionalAmount = $fetchedDetails['additional_amount'] ?? null;
            $additionalAmountReason = $fetchedDetails['additional_amount_reason'] ?? null;

            if($partialTransferCount != null)
            {
                $partialDept = null;
                $partialSubDept = null;
                $partialOffices = null;
                $partialNodalName = null;
                $partialPioName = null;
                $partialApioName = null;
                $partialReason = null;
                for($i=0; $i<$partialTransferCount; $i++)
                {
                    $partialDept[$i] = $fetchedDetails['partial_departments'][$i];
                    $partialSubDept[$i] = $fetchedDetails['partial_subdepartments'][$i];
                    $partialOffices[$i] = $fetchedDetails['partial_offices'][$i];
                    $partialNodalName[$i] = $fetchedDetails['partial_nodal_officer'][$i];
                    $partialPioName[$i] = $fetchedDetails['partial_pio_officer'][$i];
                    $partialApioName[$i] = $fetchedDetails['partial_apio_officer'][$i];
                    $partialReason[$i] = $fetchedDetails['partialReason'][$i];
                }
            }

            $existingActionDetails = [];

            if ($submitRequestDetails && isset($submitRequestDetails['reg_no'])) {
                $existingActionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->latest()->first();
            }

            $actionDescription = Actions::where('id', $actionSelected)->first();
            // $deptId = $submitRequestDetails['applied_dept'];

            $actionDetailsData = [];
            $actionData['change'] = $actionDescription->name;
            $deptData = [];
            $actionDetailsData['updated_by'] = $deptOfficer->id;
            $actionDetailsData['ip_address'] = $request->ip();
            $actionDetailsData['mac_address'] = 'N/A';

            $deptDetails = DepartmentNew::where('dept_code',$submitRequestDetails['applied_dept'])->where('office_code', $submitRequestDetails['applied_subdept'])->where('suboffice_code',$submitRequestDetails['applied_office'])->first();

            $existingSubmitRqData = SubmitRequest::where('id', $submitRequestDetails['id'])->first();
            if($existingSubmitRqData)
            {
                switch ($actionSelected) {
                    case 1:
                        // dd('case 1');
                        $actionDetailsData['status_id'] = 2;

                        $currentActionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->latest()->first();
                        $currentActionDetailsArray = $currentActionDetails->toArray();

                        $actionDetailsFillable = $currentActionDetails->getFillable();
                        $dataToInsert = array_intersect_key($currentActionDetailsArray, array_flip($actionDetailsFillable));

                        $actionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->update($actionDetailsData);

                        $historyData = [
                            'reg_no' => $submitRequestDetails['reg_no'],
                            'changes' => $actionData['change'],
                            'history_field' => json_encode($dataToInsert),
                            'action_details_id' => $currentActionDetails->id,
                            'current_dept_id' => $submitRequestDetails['dept_id'],
                        ];
                        application_history::create($historyData);

                        break;

                    case 2:
                        // dd('case 2');
                        if($this->_isFaFiled($submitRequestDetails['reg_no']))
                        {
                            throw new Exception("First Appeal already Filed for Registration No. ".$submitRequestDetails['reg_no'].", Cannot be Forwarded.");
                        }

                        if($fetchedDetails['forwardReasonPio'] == null || $fetchedDetails['forwardReasonPio'] == '')
                        {
                            throw new Exception("Forward reason cannot be empty or null");
                        }

                        $explodeData = explode('-',$fetchedDetails['pioUser']);
                        $getPioData = dept_officers_master::where('username', trim($explodeData[0]))
                        ->where('is_active', 1)
                        ->first();

                        $this->_forwardToSubDepartment($getPioData,2,$submitRequestDetails['reg_no'],$fetchedDetails['forwardReasonPio']);

                        $currentActionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->latest()->first();
                        $currentActionDetailsArray = $currentActionDetails->toArray();

                        $actionDetailsFillable = $currentActionDetails->getFillable();
                        $dataToInsert = array_intersect_key($currentActionDetailsArray, array_flip($actionDetailsFillable));
                        $dataToInsert['dept_id'] = $getPioData->dept_id;

                        // sms for PIO
                        $var1 ="Sir/Madam";
                        $var2 = "Request";
                        $var3 = "been forward received and assigned against your PIO ID";

                        $pioMsg = "Dear ".$var1.", RTI ".$var2." registration number ".$submitRequestDetails['reg_no']." has ".$var3." - Dept. of Information and Publicity";
                        $this->_sendSms($getPioData->mobile_no,$pioMsg);

                        // sms for Applicant
                        $this->_applicantSms('Forwarded',$submitRequestDetails['reg_no'],$submitRequestDetails['mobile_no']);

                        if($currentActionDetails && $getPioData->id != null)
                        {
                            $actionDetailsData = [
                                'reg_no' => $submitRequestDetails['reg_no'],
                                'status_id' => 7,
                                'assigned_off2' => $getPioData->id,
                                'mac_address' => 'N/A',
                            ];

                            $actionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->update($actionDetailsData);

                            $historyData = [
                                'reg_no' => $submitRequestDetails['reg_no'],
                                'changes' => $actionData['change'],
                                'history_field' => json_encode($dataToInsert),
                                'action_details_id' => $currentActionDetails->id,
                                'current_dept_id' => $getPioData->dept_id,
                            ];

                            application_history::create($historyData);
                        }

                        break;

                    case 3:

                        if($this->_isFaFiled($submitRequestDetails['reg_no']))
                        {
                            throw new Exception("First Appeal already Filed for Registration No. ".$submitRequestDetails['reg_no'].", Cannot be Forwarded.");
                        }

                        if($fetchedDetails['forwardReasonApio'] == null || $fetchedDetails['forwardReasonApio'] == '')
                        {
                            throw new Exception("Forward reason cannot be empty or null");
                        }

                        $explodeData = explode('-',$fetchedDetails['apioUser']);
                        $getApioData = dept_officers_master::where('username', trim($explodeData[0]))
                        ->where('is_active', 1)
                        ->first();

                        $this->_forwardToSubDepartment($getApioData,3,$submitRequestDetails['reg_no'],$fetchedDetails['forwardReasonApio']);

                        $currentActionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->latest()->first();
                        $currentActionDetailsArray = $currentActionDetails->toArray();

                        $actionDetailsFillable = $currentActionDetails->getFillable();
                        $dataToInsert = array_intersect_key($currentActionDetailsArray, array_flip($actionDetailsFillable));
                        $dataToInsert['dept_id'] = $getApioData->dept_id;

                        // sms for APIO
                        $var1 ="Sir/Madam";
                        $var2 = "Request ";
                        $var3 = "been forward received and assigned against your APIO ID";

                        $pioMsg = "Dear ".$var1.", RTI ".$var2." registration number ".$submitRequestDetails['reg_no']." has ".$var3." - Dept. of Information and Publicity";
                        $this->_sendSms($getApioData->mobile_no,$pioMsg);

                        // sms for Applicant
                        $this->_applicantSms('Forwarded',$submitRequestDetails['reg_no'],$submitRequestDetails['mobile_no']);

                        if($currentActionDetails && $getApioData->id != null)
                        {
                            $actionDetailsData = [
                                'reg_no' => $submitRequestDetails['reg_no'],
                                'status_id' => 7,
                                'assigned_off2' => $getApioData->id,
                                'mac_address' => 'N/A',
                            ];

                            $actionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->update($actionDetailsData);

                            $historyData = [
                                'reg_no' => $submitRequestDetails['reg_no'],
                                'changes' => $actionData['change'],
                                'history_field' => json_encode($dataToInsert),
                                'action_details_id' => $currentActionDetails->id,
                                'current_dept_id' => $getApioData->dept_id,
                            ];

                            application_history::create($historyData);
                        }
                        else
                        {
                            // throw error
                        }
                        break;

                    case 4:
                            // Supporting Doc required from applicant
                            // dd('case 4');
                            $currentActionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->latest()->first();
                            $currentActionDetailsArray = $currentActionDetails->toArray();

                            $actionDetailsFillable = $currentActionDetails->getFillable();
                            $dataToInsert = array_intersect_key($currentActionDetailsArray, array_flip($actionDetailsFillable));
                            $dataToInsert['dept_id'] = $submitRequestDetails['dept_id'];

                            $actionDetailsData['status_id'] = 3;
                            if($reqDisposeReason != null)
                            {
                                $docRequired = Req_dispose_of::create([
                                    'reg_no' => $submitRequestDetails['reg_no'],
                                    'file_no' => null,
                                    'reason' => $reqDisposeReason,
                                    'disposal_type' => 'Document Required',
                                    'document_path' => null,
                                    'document_name' => null,
                                ]);
                                $actionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->update($actionDetailsData);

                                $historyData = [
                                    'reg_no' => $submitRequestDetails['reg_no'],
                                    'changes' => $actionData['change'],
                                    'history_field' => json_encode($dataToInsert),
                                    'action_details_id' => $currentActionDetails->id,
                                    'current_dept_id' => $submitRequestDetails['dept_id'],
                                ];

                                application_history::create($historyData);
                            }
                            // what flag has to be set in submit_request ?

                            break;
                    case 5:
                        // dd('case 5');

                        if($this->_isFaFiled($submitRequestDetails['reg_no']))
                        {
                            throw new Exception("First Appeal already Filed for Registration No. ".$submitRequestDetails['reg_no'].", Cannot be Transfered.");
                        }

                        if($fetchedDetails['transferReason'] == null || $fetchedDetails['transferReason'] == '')
                        {
                            throw new Exception("Transfer reason cannot be empty or null");
                        }

                        $submitRequestObj = new SubmitRequest();
                        $deptNumber = str_pad($selectedMainDept, 2, '0', STR_PAD_LEFT).''.str_pad($selectedSubDept, 2, '0', STR_PAD_LEFT).''.str_pad($selectedOfficeDept, 2, '0', STR_PAD_LEFT);
                        $deptCode = Rti_Dept_Id::where('dept_id', $deptNumber)->first();

                        $oldDeptNumber = str_pad($submitRequestDetails['applied_dept'], 2, '0', STR_PAD_LEFT).''.str_pad($submitRequestDetails['applied_subdept'], 2, '0', STR_PAD_LEFT).''.str_pad($submitRequestDetails['applied_office'], 2, '0', STR_PAD_LEFT);
                        $oldDeptCode = Rti_Dept_Id::where('dept_id', $oldDeptNumber)->first();

                        $newDeptId = $submitRequestObj->createDeptCode($selectedMainDept,$selectedSubDept,$selectedOfficeDept);
                        $newRegNumber = $submitRequestObj->genRegNumber($deptCode->pubauthcode,date('m').date('y'), $submitRequestDetails['id']);
                        $existingSubmitRqData->update([
                            // 'reg_no' => $newRegNumber,
                            'applied_dept' => $selectedMainDept,
                            'applied_subdept' => $selectedSubDept,
                            'applied_office' => $selectedOfficeDept,
                            'dept_id' => $newDeptId,
                        ]);
                        $existingSubmitRqData->save();

                        $transferToNextDept = TransferToAnotherDepartment::create([
                            'new_department_id' => $deptNumber,
                            'new_department_name' => $deptCode->department,
                            'old_department_id' => $oldDeptNumber,
                            'old_department_name' => $oldDeptCode->department,
                            'old_reg_no' => $submitRequestDetails['reg_no'],
                            'new_reg_no' => $newRegNumber,
                            'reason' => $fetchedDetails['transferReason'],
                            'meta_deta' => json_encode([
                                'old_dept_id' => $submitRequestDetails['dept_id'],
                                'new_dept_id' => $newDeptId,
                                'submit_req_id' => $submitRequestDetails['id'],
                            ]),
                        ]);

                        $currentActionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->latest()->first();
                        $currentActionDetailsArray = $currentActionDetails->toArray();

                        $actionDetailsFillable = $currentActionDetails->getFillable();
                        $dataToInsert = array_intersect_key($currentActionDetailsArray, array_flip($actionDetailsFillable));
                        $dataToInsert['dept_id'] = $newDeptId;

                        $newDepartmentOfficersNodal = dept_officers_master::where('dept_code', $selectedMainDept)
                        ->where('role', 1)
                        ->first();

                        $newDepartmentOfficersPio = dept_officers_master::where('dept_code', $selectedMainDept)->where('office_code',$selectedSubDept)->where('suboffice_code',$selectedOfficeDept)
                        ->whereIn('role', [2])->first();

                        // sms for Applicant
                        $this->_applicantSms('Transferred',$submitRequestDetails['reg_no'],$submitRequestDetails['mobile_no']);

                        // sms for PIO
                        $var1 ="Sir/Madam";
                        $var2 = "New Request filed vide";
                        $var3 = "been transfer received and assigned against your PIO ID";

                        $pioMsg = "Dear ".$var1.", RTI ".$var2." registration number ".$submitRequestDetails['reg_no']." has ".$var3." - Dept. of Information and Publicity";
                        $this->_sendSms($newDepartmentOfficersPio->mobile_no,$pioMsg);

                        if ($currentActionDetails) {
                            // $actionDetailsData = [
                            //     'reg_no' => $newRegNumber,
                            //     'status_id' => 1,
                            //     'assigned_off1' => $newDepartmentOfficersNodal[0],
                            //     'assigned_off2' => $newDepartmentOfficersPio[0],
                            //     'updated_by' => $deptOfficer->id,
                            // ];

                            $currentActionDetails->update([
                                'status_id' => 8,
                                'assigned_off1' => $newDepartmentOfficersNodal->id,
                                'assigned_off2' => $newDepartmentOfficersPio->id,
                                'updated_by' => $deptOfficer->id,
                                'ip_address' => $request->ip(),
                            ]);
                            $currentActionDetails->save();

                            // $newactionDetailsId = action_details::create($actionDetailsData);
                            $historyData = [
                                // 'reg_no' => $newRegNumber,
                                'reg_no' => $submitRequestDetails['reg_no'],
                                'changes' => $actionData['change'],
                                'history_field' => json_encode($dataToInsert),
                                'action_details_id' => $currentActionDetails->id,
                                'updated_by' => $deptOfficer->id,
                                'current_dept_id' => $newDeptId,
                            ];

                            application_history::create($historyData);
                        }
                        break;

                    case 6:
                        // dd('case 6');
                        // REQUEST RETURN TO APPLICANT
                        $actionDetailsData['status_id'] = 3;
                        if($documentReqReason != null)
                        {
                            $docRequired = Req_to_applicant::create([
                                'reg_no' => $submitRequestDetails['reg_no'],
                                'reason' => $documentReqReason,
                                'meta_data' => 'N/A',
                            ]);

                            $currentActionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->latest()->first();
                            $currentActionDetailsArray = $currentActionDetails->toArray();

                            $actionDetailsFillable = $currentActionDetails->getFillable();
                            $dataToInsert = array_intersect_key($currentActionDetailsArray, array_flip($actionDetailsFillable));
                            $dataToInsert['dept_id'] = $submitRequestDetails['dept_id'];

                            $actionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->update($actionDetailsData);

                            // sms for Applicant
                            $this->_applicantSms('Returned',$submitRequestDetails['reg_no'],$submitRequestDetails['mobile_no']);

                            $historyData = [
                                'reg_no' => $submitRequestDetails['reg_no'],
                                'changes' => $actionData['change'],
                                'history_field' => json_encode($dataToInsert),
                                'action_details_id' => $currentActionDetails->id,
                                'current_dept_id' => $submitRequestDetails['dept_id'],
                            ];

                            application_history::create($historyData);
                            // what flag has to be set in submit_request ?
                        }

                        break;

                    case 7:
                        $actionDetailsData['status_id'] = 9;

                        if($additionalAmount < 25)
                        {
                            throw new Exception("Additional Charges Cannot be less than Rs. 25/-");
                        }
                        if($modeInformationSupply != null)
                        {
                            $docRequired = Additional_Payment_details::create([
                                'reg_no' => $submitRequestDetails['reg_no'],
                                'mode_of_info_supply' => $modeInformationSupply,
                                'payment_amount' => $additionalAmount,
                                'reason' => $additionalAmountReason,
                            ]);
                            $currentActionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->latest()->first();
                            $currentActionDetailsArray = $currentActionDetails->toArray();

                            // sms for Applicant
                            $this->_applicantSms("Charged Rs. ".$additionalAmount,$submitRequestDetails['reg_no'],$submitRequestDetails['mobile_no']);

                            $actionDetailsFillable = $currentActionDetails->getFillable();
                            $dataToInsert = array_intersect_key($currentActionDetailsArray, array_flip($actionDetailsFillable));
                            $dataToInsert['dept_id'] = $submitRequestDetails['dept_id'];
                            $actionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->update($actionDetailsData);

                            $historyData = [
                                'reg_no' => $submitRequestDetails['reg_no'],
                                'changes' => $actionData['change'],
                                'history_field' => json_encode($dataToInsert),
                                'action_details_id' => $currentActionDetails->id,
                                'current_dept_id' => $submitRequestDetails['dept_id'],
                            ];

                            application_history::create($historyData);
                        }
                        break;

                    case 8:
                        // dd('case 8');
                        // REQUEST DISPOSE OF

                        DB::beginTransaction();
                        try
                        {
                            $dispose_of = $fetchedDetails['dispose_of'] ?? null;
                            $fileNoDisposal = $fetchedDetails['file_no_disposal'] ?? null;
                            $disposalReason = $fetchedDetails['info_provided_citizen'] ?? null;
                            $disposalType = $fetchedDetails['disposal_type'] ?? null;

                            $fileName = null;
                            $path = null;

                            if ($request->hasFile('reply_doc'))
                            {
                                $folderPath = 'public/Document_Reply/'.$deptDetails->dept_code;
                                if (!Storage::exists($folderPath)) {
                                    Storage::makeDirectory($folderPath);
                                }

                                $file = $request->file('reply_doc');
                                $fileNameSplit = explode('/', $submitRequestDetails['reg_no']);

                                $fileName = $fileNameSplit[3].'_'.date('dmyHi').'_REPLY.'.$file->getClientOriginalExtension();

                                $path = Storage::putFileAs(
                                    $folderPath, $file, $fileName
                                );
                            }
                            if ($request->hasFile('reply_doc1'))
                            {
                                $folderPath1 = 'public/Document_Reply/'.$deptDetails->dept_code;
                                if (!Storage::exists($folderPath1)) {
                                    Storage::makeDirectory($folderPath1);
                                }

                                $file1 = $request->file('reply_doc1');
                                $fileNameSplit = explode('/', $submitRequestDetails['reg_no']);

                                $fileName1 = $fileNameSplit[3].'_'.date('dmyHi').'_REPLY_1.'.$file1->getClientOriginalExtension();

                                $path1 = Storage::putFileAs(
                                    $folderPath1, $file1, $fileName1
                                );
                            }
                            if ($request->hasFile('reply_doc2'))
                            {
                                $folderPath2 = 'public/Document_Reply/'.$deptDetails->dept_code;
                                if (!Storage::exists($folderPath2)) {
                                    Storage::makeDirectory($folderPath2);
                                }

                                $file2 = $request->file('reply_doc2');
                                $fileNameSplit = explode('/', $submitRequestDetails['reg_no']);

                                $fileName2 = $fileNameSplit[3].'_'.date('dmyHi').'_REPLY_2.'.$file2->getClientOriginalExtension();

                                $path2 = Storage::putFileAs(
                                    $folderPath2, $file2, $fileName2
                                );
                            }
                            else
                            {
                                echo "Possible file upload attack!\n";
                            }

                            $reqDisposeCreate = [
                                'reg_no' => $submitRequestDetails['reg_no'],
                                'file_no' => $fileNoDisposal,
                                'reason' => $disposalReason,
                                'disposal_type' => $disposalType,
                                'document_path' => $path,
                                'document_name' => $fileName,
                                'original_doc_name' => $file->getClientOriginalName()
                            ];

                            if ($request->hasFile('reply_doc1'))
                            {
                                $reqDisposeCreate['document_path1'] = $path1;
                                $reqDisposeCreate['document_name1'] = $fileName1;
                                $reqDisposeCreate['original_doc_name1'] = $file1->getClientOriginalName();
                            }
                            if ($request->hasFile('reply_doc2'))
                            {
                                $reqDisposeCreate['document_path2'] = $path2;
                                $reqDisposeCreate['document_name2'] = $fileName2;
                                $reqDisposeCreate['original_doc_name2'] = $file2->getClientOriginalName();
                            }

                            // dd($reqDisposeCreate);
                            $docRequired = Req_dispose_of::create($reqDisposeCreate);

                            $actionDetailsData['status_id'] = 5;
                            $actionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->update($actionDetailsData);

                            $this->_applicantSms('Replied',$submitRequestDetails['reg_no'],$submitRequestDetails['mobile_no']);

                            $currentActionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->latest()->first();
                            $currentActionDetailsArray = $currentActionDetails->toArray();

                            $actionDetailsFillable = $currentActionDetails->getFillable();
                            $dataToInsert = array_intersect_key($currentActionDetailsArray, array_flip($actionDetailsFillable));
                            $dataToInsert['dept_id'] = $submitRequestDetails['dept_id'];

                            $historyData = [
                                'reg_no' => $submitRequestDetails['reg_no'],
                                'changes' => $actionData['change'],
                                'history_field' => json_encode($dataToInsert),
                                'action_details_id' => $currentActionDetails->id,
                                'current_dept_id' => $submitRequestDetails['dept_id'],
                            ];

                            application_history::create($historyData);

                            DB::commit();
                        }
                        catch(Exception $exception)
                        {
                            DB::rollback();
                            throw $exception;
                        }

                        break;
                    case 9:
                        // dd('case 9');
                        $fileNumber = $fetchedDetails['file_no'] ?? null;
                        $reqReasonTherefore = $fetchedDetails['reason_therefore'] ?? null;
                        $actionDetailsData['status_id'] = 4;

                        if($fileNumber != null)
                        {
                            $meta['file_no'] = $fileNumber;
                            $meta = json_encode($meta);
                        }
                        else
                        {
                            $meta = 'N/A';
                        }

                        $docRequired = Req_to_applicant::create([
                            'reg_no' => $submitRequestDetails['reg_no'],
                            'reason' => $reqReasonTherefore,
                            'meta_data' => $meta,
                        ]);

                        $currentActionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->latest()->first();
                        $currentActionDetailsArray = $currentActionDetails->toArray();

                        $actionDetailsFillable = $currentActionDetails->getFillable();
                        $dataToInsert = array_intersect_key($currentActionDetailsArray, array_flip($actionDetailsFillable));
                        $dataToInsert['dept_id'] = $submitRequestDetails['dept_id'];

                        $actionDetails = action_details::where('reg_no', $submitRequestDetails['reg_no'])->update($actionDetailsData);
                        $historyData = [
                            'reg_no' => $submitRequestDetails['reg_no'],
                            'changes' => $actionData['change'],
                            'history_field' => json_encode($dataToInsert),
                            'action_details_id' => $currentActionDetails->id,
                            'current_dept_id' => $submitRequestDetails['dept_id'],
                        ];

                        application_history::create($historyData);
                        break;

                    case 18:

                        DB::beginTransaction();
                        try
                        {
                            if($this->_isFaFiled($submitRequestDetails['reg_no']))
                            {
                                throw new Exception("First Appeal already Filed for Registration No. ".$submitRequestDetails['reg_no'].", Cannot be Partially Transfered.");
                            }

                            if($submitRequestDetails['action_details'][0]['status_id'] == 6)
                            {
                                throw new Exception("Partial Request cannot be Partially Transfered Again");
                            }

                            $exists = SubmitRequest::where('reg_no', 'LIKE', $submitRequestDetails['reg_no'] . '/%')
                            ->where('reg_no', '!=', $submitRequestDetails['reg_no'])
                            ->exists();

                            if ($exists) {
                                throw new Exception("This application is already partially transferred.");
                            }

                            $submitRequestData = SubmitRequest::where('reg_no',$submitRequestDetails['reg_no'])->first();
                            // dd($submitRequestData->name, $submitRequestData->reg_no);

                            for($i=0; $i< $partialTransferCount; $i++)
                            {
                                // query for dept verification

                                $departmentQuery = DepartmentNew::where('dept_code',$partialDept[$i])->where('office_code', $partialSubDept[$i])->where('suboffice_code',$partialOffices[$i])->first();

                                $submitRequestObj = new SubmitRequest();
                                $partialDeptCode[$i] = $submitRequestObj->createDeptCode($partialDept[$i],$partialSubDept[$i],$partialOffices[$i]);

                                // nodal query
                                $nodalUser = explode('-',$partialNodalName[$i]);
                                $nodalQuery = dept_officers_master::where('username', trim($nodalUser[0]))->where('is_active', 1)->first();

                                // pio query
                                $pioUser = explode('-',$partialPioName[$i]);
                                $pioQuery = dept_officers_master::where('username', trim($pioUser[0]))->where('is_active', 1)->first();

                                // apio query
                                $apioUser = explode('-',$partialApioName[$i]);
                                $apioQuery = dept_officers_master::where('username', trim($apioUser[0]))->where('is_active', 1)->first();

                                $submitRequestObj[$i] = new SubmitRequest();

                                // $deptCode[$i] = Rti_Dept_Id::where('dept_id', $partialDeptCode[$i])->first();

                                // old logic to create new reg_no
                                // $newRegNumber = $submitRequestObj[$i]->genRegNumber($deptCode[$i]->pubauthcode,date('m').date('y'), $submitRequestDetails['id']);

                                // can use eixsting reg_no
                                $newRegNumber[$i] = $submitRequestDetails['reg_no'].'/'.$i+1;

                                $submitReqInsert[$i] = [
                                    'applied_dept' => $partialDept[$i],
                                    'applied_subdept' => $partialSubDept[$i],
                                    'applied_office' => $partialOffices[$i],
                                    'name' => $submitRequestData->name,
                                    'gender' => $submitRequestData->gender,
                                    'address_line1' => $submitRequestData->address_line1,
                                    'address_line2' => $submitRequestData->address_line2,
                                    'address_line3' => $submitRequestData->address_line3,
                                    'pincode' => $submitRequestData->pincode,
                                    'country' => $submitRequestData->country,
                                    'state' => $submitRequestData->state,
                                    'district' => $submitRequestData->district,
                                    'taluka' => $submitRequestData->taluka,
                                    'village' => $submitRequestData->village,
                                    'phone_no' => $submitRequestData->phone_no,
                                    'mobile_no' => $submitRequestData->mobile_no,
                                    'email_id' => $submitRequestData->email_id,
                                    'edu_status' => $submitRequestData->edu_status,
                                    'qualification' => $submitRequestData->qualification,
                                    'origin_country' => $submitRequestData->origin_country,
                                    'is_bpl' => $submitRequestData->is_bpl,
                                    'card_no' => $submitRequestData->card_no,
                                    'issued_year' => $submitRequestData->issued_year,
                                    'issuing_authority' => $submitRequestData->issuing_authority,
                                    'request_text' => $partialReason[$i],
                                    'document_name' => $submitRequestData->document_name,
                                    'document_path' => $submitRequestData->document_path,
                                    'reg_no' => $newRegNumber[$i],
                                    'payment_details' => $submitRequestData->payment_details,
                                    'document_name_original' => $submitRequestData->document_name_original,
                                    'dept_id' => $partialDeptCode[$i],
                                ];

                                $submitInsertQ[$i] = SubmitRequest::create($submitReqInsert[$i]);

                                $actionDetailsData[$i] = [
                                    'reg_no' => $newRegNumber[$i],
                                    'status_id' => 6,
                                    'assigned_off1' => $nodalQuery ? $nodalQuery->id : null,
                                    'assigned_off2' => $pioQuery ? $pioQuery->id : null,
                                    'updated_by' => $deptOfficer->id,
                                    'ip_address' => $request->ip(),
                                    'mac_address' => 'N/A',
                                ];
                                $actionDetailsId[$i] = action_details::create($actionDetailsData[$i]);

                                $fillableFields = $submitRequestObj->getFillable();
                                $dataToInsert = array_intersect_key($submitReqInsert[$i], array_flip($fillableFields));

                                $historyData[$i] = [
                                    'reg_no' => $newRegNumber[$i],
                                    'changes' => 'PARTIAL TRANSFER OF SUBMITTED APPLICATION',
                                    'history_field' => json_encode($dataToInsert),
                                    'action_details_id' => $actionDetailsId[$i]->id,
                                    'current_dept_id' => $partialDeptCode[$i],
                                ];

                                application_history::create($historyData[$i]);

                                $applicantRegData = [
                                    'dept_id' => $partialDeptCode[$i],
                                    'reg_no' => $newRegNumber[$i],
                                    'regmobile_no' => $submitRequestData->mobile_no,
                                    'email_id' => $submitRequestData->email_id,
                                ];

                                RtiApplicantRegdata::create($applicantRegData);
                            }

                            DB::commit();
                        }
                        catch(Exception $exception)
                        {
                            DB::rollback();
                            throw $exception;
                        }
                        break;

                    default:
                    // dd('in default switch case');
                        $actionDetailsData = 'N/A';
                        break;
                }
            }
        }
        catch(Exception $ex)
        {
            return redirect('/RTIMIS/dashboard')->with('warning', $ex->getMessage());
        }

        return redirect('/RTIMIS/dashboard')->with('success', 'Change Request has been submitted');
    }

    public function firstAppealDetailedView($id)
    {
        try
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

            if($faDetails == null)
                throw new exception('Data Missing(Either First Appeal or New Request Data Missing)');

            $villageFetch = Village::where('id',$faDetails->village)->first();
            $districtFetch = District::where('id',$faDetails->district)->first();

            $pioResonse = pioResponse::where('id',$faDetails->pioresponse)->first();
            $uploadedDocs = fa_uploaded_documents::where('reg_no',$faDetails['reg_no'])->get();
            $pioReplyDoc = $uploadedDocs->where('document_category','7')->first();
            $supportingDoc = $uploadedDocs->where('document_category','2')->first();
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
                'village' => $villageFetch == null ? 'Other' : $villageFetch->name,
                'district' => $districtFetch == null ? 'Other' : $districtFetch->name,
            ];


        $faAction = FaActions::where('fa_reg_no', $faDetails->fa_reg_no)
        ->first();

        if ($faAction && !empty($faAction->document_path)) {

            $cleanPath = preg_replace('/^(public\/|storage\/)/', '', $faAction->document_path);

            $faAction->document_path = 'storage/'.$cleanPath;
        }

        $reqestDisposeOf = Req_dispose_of::where('reg_no',$faDetails['reg_no'])->first();
          $officerFile = [];

        if($reqestDisposeOf!=null)
        {
            $documentPaths = [
            $reqestDisposeOf['document_path'],
            $reqestDisposeOf['document_path1'],
            $reqestDisposeOf['document_path2']
        ];


        foreach ($documentPaths as $documentPath) {
                if ($documentPath) {
                    $officerFile[] = Storage::url($documentPath);
                } else {
                    $officerFile[] = null;
                }
            }
        }
        $requestToApplicant = Req_to_applicant::where('reg_no',$faDetails->reg_no)->first();
            return view('firstAppeal.firstAppealDetailedView', compact('faDetails','reqestDisposeOf','officerFile','faAction','requestToApplicant', 'submitReq', 'departmentDetails', 'userDetails', 'actionsData','uploadedDocs'));
        }
        catch(Exception $ex)
        {
            return redirect('/RTIMIS/dashboard/{new_FA}')->with('warning', $ex->getMessage());
        }
    }

    public function saveFaActions(Request $request)
    {
        // dump($request->all());
        $currentuser = session('username');
        $deptOfficer = dept_officers_master::where('username', $currentuser)->where('is_active', 1)->first();

        $faSubmitDetails = json_decode($request->get('faSubmitDetails'));
        $selectedAction = $request->get('selectedAction');
        $disposalReason = $request->get('disposal_reason');
        $faaComments = $request->get('spio_comments');
        $returnToApplicantReason = $request->get('return_reason');

        $faReplyDoc = $request->file('fa_reply_doc');

        try
        {
            // dump($faSubmitDetails, $selectedAction, $disposalReason, $faaComments, $faReplyDoc);

            $faApplicationStatus = fa_application_status::where('fa_reg_no', $faSubmitDetails->fa_reg_no)->latest()->first();
            $currentDetails = $faApplicationStatus->toArray();
            $actionDetailsFillable = $faApplicationStatus->getFillable();
            $dataToInsert = array_intersect_key($currentDetails, array_flip($actionDetailsFillable));
            $dataToInsert['dept_id'] = $faSubmitDetails->dept_id;

            $updateHistoryDetails = [
                'fa_reg_no' => $faSubmitDetails->fa_reg_no,
                'fa_application_id' => $faApplicationStatus->id,
                'history_field' => json_encode($dataToInsert),
            ];

            // $faSubmitDetails->applied_dept
            $submitRequestObj = new SubmitRequest();
            $deptCode = $submitRequestObj->createDeptCode($faSubmitDetails->applied_dept,$faSubmitDetails->applied_subdept,$faSubmitDetails->applied_office);
            // $deptDetails = DepartmentView::where('dept_id', $deptCode)->first();
            $deptDetails = DepartmentNew::where('dept_code',$faSubmitDetails->applied_dept)->where('office_code', $faSubmitDetails->applied_subdept)->where('suboffice_code',$faSubmitDetails->applied_office)->first();
            $deptData = dept_officers_master::where('dept_code', substr($deptCode,0,2))->get();
            $nodalUser = $deptData->where('role', 1)->first();
            $faaUser = $deptData->where('role', 4)->first();
            // $pioUser = $deptData->where('role', 2)->first();

            switch($selectedAction)
            {
                case '10':
                    $updateHistoryDetails['changes'] = 'ADMIT APPEAL FOR EXAMINATION';

                    $saveActionDetailsId = FaActionDetails::create($updateHistoryDetails)->id;

                    $updateAplicationDetails = [
                        'status_id' => 2,
                        'action_id' => 10,
                        'reg_no' => $faSubmitDetails->reg_no,
                        'updated_by' => $deptOfficer->id,
                    ];

                    $updateFaApplication = fa_application_status::where('fa_reg_no', $faSubmitDetails->fa_reg_no)->update($updateAplicationDetails);

                    // $saveData = FaActions::create([
                    //     'fa_reg_no' => $faSubmitDetails->fa_reg_no,
                    //     'fa_action_details_id' => $saveActionDetailsId,
                    //     'action_id' => $selectedAction,
                    //     'disposal_reason' => null,
                    //     'spio_comments' => null,
                    //     'fa_reply_doc' => null,
                    //     'updated_by' => $deptOfficer->id
                    // ]);

                break;

                case '11':

                    $updateHistoryDetails['changes'] = 'APPEAL DISPOSE OF';

                    $saveActionDetailsId = FaActionDetails::create($updateHistoryDetails)->id;
                    $updateAplicationDetails = [
                        'status_id' => 5,
                        'action_id' => 11,
                        'reg_no' => $faSubmitDetails->reg_no,
                        'updated_by' => $deptOfficer->id,
                    ];

                    $updateFaApplication = fa_application_status::where('fa_reg_no', $faSubmitDetails->fa_reg_no)->update($updateAplicationDetails);

                    $date = Carbon::now();
                    $dateString = $date->format('dmy');

                    if ($request->hasFile('fa_reply_doc'))
                    {
                        $folderPath = 'public/First_Appeal_ReplyDocs/' . $deptDetails->dept_code;
                        if (!Storage::exists($folderPath)) {
                            Storage::makeDirectory($folderPath);
                        }

                        $file = $request->file('fa_reply_doc');
                        $originalFileName = $file->getClientOriginalName();
                        // $lastInsertedId = FaActions::where('fa_reg_no', $faSubmitDetails->fa_reg_no)->orderBy('id', 'desc');
                        // dd('issue');

                        $lastInsertedRecord = FaActions::where('fa_reg_no', $faSubmitDetails->fa_reg_no)->orderBy('id', 'desc')->first();
                        if($lastInsertedRecord)
                        {
                            $lastInsertedId = $lastInsertedRecord->id;
                        }
                        else
                        {
                            $lastInsertedId = null;
                        }

                        // dd($folderPath,$lastInsertedId);
                        $currentId = is_null($lastInsertedId) ? 1 : $lastInsertedId + 1;
                        // dd($currentId);
                        $fileName = $deptDetails->dept_code .'_'. $dateString .'_'.$currentId.'.'. $file->getClientOriginalExtension();

                        // dd($fileName);
                        $path = Storage::putFileAs(
                            $folderPath, $file, $fileName
                        );

                        // dd($path,$fileName,$path,$originalFileName);

                        $saveData = FaActions::create([
                            'fa_reg_no' => $faSubmitDetails->fa_reg_no,
                            'fa_action_details_id' => $saveActionDetailsId,
                            'action_id' => $selectedAction,
                            'disposal_reason' => $disposalReason,
                            'spio_comments' => null,
                            'fa_reply_doc' => $faReplyDoc,
                            'document_name' => $fileName,
                            'document_origial_name' => $originalFileName,
                            'document_path' => $path,
                            'updated_by' => $deptOfficer->id
                        ]);

                    }
                break;

                case '12':

                    $checkComment = FaActions::where('fa_reg_no',$faSubmitDetails->fa_reg_no)->first();
                    if($checkComment)
                    {
                        if($checkComment->faa_comments)
                        throw new exception('The FAA Comments were already requested');
                    }

                    $updateHistoryDetails['changes'] = 'SEEK COMMENTS FROM SPIO';
                    $existingDetail = FaActionDetails::where('fa_reg_no', $faSubmitDetails->fa_reg_no)
                        ->where('changes', 'SEEK COMMENTS FROM SPIO')
                        ->first();

                    if(!$existingDetail)
                    {
                        $saveActionDetailsId = FaActionDetails::create($updateHistoryDetails)->id;
                    }
                    else
                    {
                        $saveActionDetailsId = $existingDetail->id;
                    }

                    $updateAplicationDetails = [
                        'status_id' => 2,
                        'action_id' => 12,
                        'reg_no' => $faSubmitDetails->reg_no,
                        'updated_by' => $deptOfficer->id,
                    ];

                    $updateFaApplication = fa_application_status::where('fa_reg_no', $faSubmitDetails->fa_reg_no)->update($updateAplicationDetails);

                    $saveData = FaActions::create([
                        'fa_reg_no' => $faSubmitDetails->fa_reg_no,
                        'fa_action_details_id' => $saveActionDetailsId,
                        'action_id' => $selectedAction,
                        'disposal_reason' => null,
                        'faa_comments' => $faaComments,
                        'fa_reply_doc' => null,
                        'updated_by' => $deptOfficer->id
                    ]);

                break;

                case '13':
                    // RETURN FIRST APPEAL TO APPLICANT
                    // if($pioUser->id != null)
                    // {
                    //     dd('die');
                    //     $pioUser->id = 88;
                    // }
                    $updateHistoryDetails['changes'] = 'RETURN FIRST APPEAL TO APPLICANT';

                    $saveActionDetailsId = FaActionDetails::create($updateHistoryDetails)->id;

                    $updateAplicationDetails = [
                        'status_id' => 3,
                        'action_id' => 13,
                        'reg_no' => $faSubmitDetails->reg_no,
                        'updated_by' => $deptOfficer->id,
                    ];

                    $updateFaApplication = fa_application_status::where('fa_reg_no', $faSubmitDetails->fa_reg_no)->update($updateAplicationDetails);

                    $saveData = FaActions::create([
                        'fa_reg_no' => $faSubmitDetails->fa_reg_no,
                        'fa_action_details_id' => $saveActionDetailsId,
                        'action_id' => null,
                        'disposal_reason' => null,
                        'spio_comments' => null,
                        'fa_reply_doc' => null,
                        'updated_by' => null,
                        'return_to_applicant_reason'=> $returnToApplicantReason
                    ]);
                    break;

                case '14':

                    $updateHistoryDetails['changes'] = 'FORWARD THE FIRST APPEAL TO FIRST APPELLATE AUTHORITY';

                    $saveActionDetailsId = FaActionDetails::create($updateHistoryDetails)->id;

                    $updateAplicationDetails = [
                        'status_id' => 8,
                        'action_id' => 14,
                        'reg_no' => $faSubmitDetails->reg_no,
                        'updated_by' => $deptOfficer->id,
                        'assigned_off1' => $faaUser->id,
                        // 'assigned_off2' => 0,
                    ];

                    $updateFaApplication = fa_application_status::where('fa_reg_no', $faSubmitDetails->fa_reg_no)->update($updateAplicationDetails);

                    // $saveData = FaActions::create([
                    //     'fa_reg_no' => $faSubmitDetails->fa_reg_no,
                    //     'fa_action_details_id' => $saveActionDetailsId,
                    //     'action_id' => $selectedAction,
                    //     'disposal_reason' => null,
                    //     'spio_comments' => null,
                    //     'fa_reply_doc' => null,
                    //     'updated_by' => $deptOfficer->id
                    // ]);
                    break;

                default:

                    break;
            }
        }
        catch(Exception $e)
        {
            return redirect('/RTIMIS/dashboard/{new_FA}')->with('error', $e->getMessage());
        }

        // dd('checking end point');
        return redirect('/RTIMIS/dashboard/{new_FA}')->with('success', 'Change Request has been submitted');
    }

    public function departmentStatusNR(Request $request)
    {
        $search = $request->input('search');
        // $deptId = ltrim(substr(session('deptId'), 0, 2), '0');
        // $status = AppReqStatus::where('applied_dept',$deptId)->get();

        $deptOfficer = dept_officers_master::where('username', session('username'))->where('is_active', 1)->first();

        $category = 'Department wise actions completed';
        $status = rti_application_actions::where('applied_dept', ltrim(substr(session('deptId'), 0, 2), '0'))
        ->where('reg_no', 'LIKE', '%' . 'NR' . '%')
        ->orderby('date_of_efiling', 'desc')
        ->orderby('app_history_created', 'desc')
        ->when($search, function ($query, $search) {
            return $query->where('reg_no', 'like', "%{$search}%")
                         ->orWhere('applicant_name', 'like', "%{$search}%");
        })->paginate(10);

        $currentPage = $status->currentPage();
        $perPage = $status->perPage();

        return view('DeptApplicationStatus', compact('status', 'category', 'currentPage', 'perPage'));
    }

    public function departmentStatusFA(Request $request)
    {
        $search = $request->input('search');
        // $deptId = ltrim(substr(session('deptId'), 0, 2), '0');
        // $status = AppReqStatus::where('applied_dept',$deptId)->get();

        $deptOfficer = dept_officers_master::where('username', session('username'))->where('is_active', 1)->first();

        $category = 'First Appellate - Department wise actions completed';
        $status = rti_application_actions::where('applied_dept', ltrim(substr(session('deptId'), 0, 2), '0'))
        ->where('reg_no', 'LIKE', '%' . 'FA' . '%')
        ->orderby('date_of_efiling', 'desc')
        ->orderby('app_history_created', 'desc')
        ->when($search, function ($query, $search) {
            return $query->where('reg_no', 'like', "%{$search}%")
                         ->orWhere('applicant_name', 'like', "%{$search}%");
        })->paginate(10);

        $currentPage = $status->currentPage();
        $perPage = $status->perPage();

        return view('DeptApplicationStatus', compact('status', 'category', 'currentPage', 'perPage'));
    }

    public function appStatusNR(Request $request)
    {
        $search = $request->input('search');
        $deptOfficer = dept_officers_master::where('username', session('username'))->where('is_active', 1)->first();

        $filter = 'NR';
        $category = 'Application wise actions completed';

        $status = rti_application_actions::where(function($query) use ($deptOfficer, $filter) {
            if ($deptOfficer->role == 1) {
                $query->where('applied_dept', ltrim(substr(session('deptId'), 0, 2), '0'));
            } else {
                $query->where('dept_id', session('deptId'));
            }
        })
        ->where('reg_no', 'LIKE', '%' . $filter . '%')
        ->orderBy('date_of_efiling', 'desc')->orderBy('app_history_created', 'desc')
        ->when($search, function ($query, $search) {
            return $query->where('reg_no', 'like', "%{$search}%")
                         ->orWhere('applicant_name', 'like', "%{$search}%");
        })->paginate(10);

        $currentPage = $status->currentPage();
        $perPage = $status->perPage();

        return view('DeptApplicationStatus', compact('status', 'category', 'currentPage', 'perPage'));
    }

    public function appStatusFA(Request $request)
    {
        $search = $request->input('search');
        $deptOfficer = dept_officers_master::where('username', session('username'))->where('is_active', 1)->first();


        $filter = 'FA';
        $category = 'First Appellate - Application wise actions completed';

        $status = rti_application_actions::where(function($query) use ($deptOfficer, $filter) {
            if ($deptOfficer->role == 1) {
                $query->where('applied_dept', ltrim(substr(session('deptId'), 0, 2), '0'));
            } else {
                $query->where('dept_id', session('deptId'));
            }
        })
        ->where('reg_no', 'LIKE', '%' . $filter . '%')
        ->orderBy('date_of_efiling', 'desc')->orderBy('app_history_created', 'desc')
        ->when($search, function ($query, $search) {
            return $query->where('reg_no', 'like', "%{$search}%")
                         ->orWhere('applicant_name', 'like', "%{$search}%");
        })->paginate(10);

        $currentPage = $status->currentPage();
        $perPage = $status->perPage();

        return view('DeptApplicationStatus', compact('status', 'category', 'currentPage', 'perPage'));
    }

    public function statusNR(Request $request)
    {
        $search = $request->input('search');
        $deptOfficer = dept_officers_master::where('username', session('username'))->where('is_active', 1)->first();


        $category = 'Received Applications Status';
        $filter = 'NR';

        // dd(session('deptId'),$deptOfficer->id);
        $status = rti_received_application_status::where(function($query) use ($deptOfficer, $filter) {
            if ($deptOfficer->role == 1) {
                $query->where('applied_dept', ltrim(substr(session('deptId'), 0, 2), '0'));
            } else {
                $query->where('dept_id', session('deptId'));
            }
        })
        ->where(function($query) use ($deptOfficer, $filter) {
            $query->where('assigned_off1', $deptOfficer->id)
                ->orWhere('assigned_off2', $deptOfficer->id);
        })
        ->where('reg_no', 'LIKE', '%' . $filter . '%')
        ->orderBy('date_of_efiling', 'desc')
        ->when($search, function ($query, $search) {
            return $query->where('reg_no', 'like', "%{$search}%")
                         ->orWhere('applicant_name', 'like', "%{$search}%");
        })->paginate(10);

        return view('AppStatus',compact('status','category'));
    }

    public function statusFA(Request $request)
    {
        $search = $request->input('search');
        $deptOfficer = dept_officers_master::where('username', session('username'))->where('is_active', 1)->first();

        $category = 'First Appellate - Received Applications Status';
        $filter = 'FA';
        // dd(session('deptId'),$deptOfficer->id);
        $status = rti_received_application_status::where(function($query) use ($deptOfficer, $filter) {
            if ($deptOfficer->role == 1) {
                $query->where('applied_dept', ltrim(substr(session('deptId'), 0, 2), '0'));
            } else {
                $query->where('dept_id', session('deptId'));
            }
        })
        ->where(function($query) use ($deptOfficer, $filter) {
            $query->where('assigned_off1', $deptOfficer->id)
                ->orWhere('assigned_off2', $deptOfficer->id);
        })
        ->where('reg_no', 'LIKE', '%' . $filter . '%')
        ->orderBy('date_of_efiling', 'desc')
        ->when($search, function ($query, $search) {
            return $query->where('reg_no', 'like', "%{$search}%")
                         ->orWhere('applicant_name', 'like', "%{$search}%");
        })->paginate(10);

        return view('AppStatus',compact('status','category'));
    }

    public function applicationStatus(Request $request)
    {
        $search = $request->input('search');
        $mobile = session('mobile_no');

        // Get page numbers for each paginator
        $nrPage = $request->input('nr_page', 1);
        $faPage = $request->input('fa_page', 1);
        $saPage = $request->input('sa_page', 1);

        // Fetch status lists
        $nrStatus = $this->_fetchStatus(AppReqStatus::class, $mobile, $search, $nrPage, 'nr_page');
        $faStatus = $this->_fetchStatus(rtiStatusFaReq::class, $mobile, $search, $faPage, 'fa_page');
        $saStatus = $this->_fetchStatus(rtiStatusSaReq::class, $mobile, $search, $saPage, 'sa_page');

        // Get the divSelector from request
        $divSelector = $request->input('divSelector');

        // Determine which tab should be active
        if ($search) {
            // Auto-select the tab with results if searching
            if ($nrStatus->total() > 0) {
                $divSelector = 'display1';
            } elseif ($faStatus->total() > 0) {
                $divSelector = 'display2';
            } elseif ($saStatus->total() > 0) {
                $divSelector = 'display3';
            } else {
                $divSelector = 'display1';
            }
        } else {
            // If not searching, respect divSelector or default to NR
            $divSelector = $divSelector ?? 'display1';
        }

        return view('ApplicationStatus', compact('nrStatus', 'faStatus', 'saStatus', 'search', 'divSelector'));
    }

    private function _fetchStatus($model, $mobile, $search, $page, $pageName)
    {
        return $model::where(function ($query) use ($mobile) {

                $query->where('applicant_mobile_no', $mobile)
                    ->orWhere('regmobile_no', $mobile);
            })
            ->when($search, function ($query, $search) {

                $query->where(function ($q) use ($search) {
                    $q->where('reg_no', 'like', "%{$search}%")
                    ->orWhere('applicant_name', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
                });
            })
            ->paginate(10, ['*'], $pageName, $page)
            ->appends(['search' => $search]);
    }

    public function getNodalName($depId)
    {
        $nodalDetails =  dept_officers_master::where('dept_code', $depId)
        ->where('is_active',1)
        ->where('role', 1)
        ->first();

        return response()->json($nodalDetails);
    }

    public function getOfficerName($role, Request $requestData)
    {
        $officerDetails = dept_officers_master::where('dept_code', $requestData->query('deptId'))
            ->where('office_code', $requestData->query('subDeptId'))
            ->where('suboffice_code', $requestData->query('officeId'))
            ->where('role', $role)
            ->where('is_active',1)
            ->first();

        return response()->json($officerDetails);
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

    private function _isFaFiled($registrationNumber)
    {
        $firstAppeal = fa_submit_request::where('reg_no',$registrationNumber)->first();

        if(isset($firstAppeal))
            return 1;
        else
            return 0;
    }

    private function _forwardToSubDepartment($deptUserData,$role,$regNo,$reason)
    {
        // $officerDetail = dept_officers_master::where('id', $userId)->where('role', $role)->first();

        $submitReq = SubmitRequest::where('reg_no',$regNo)->first();
        $oldDeptCode = Rti_Dept_Id::where('dept_id', $submitReq->dept_id)->first();
        $newDeptCode = Rti_Dept_Id::where('dept_id', $deptUserData->dept_id)->first();

        $transferToNextDept = ForwardToSubDepartment::create([
            'reg_no' => $submitReq->reg_no,
            'old_department_id' => $submitReq->dept_id,
            'old_department_name' => $oldDeptCode->department,
            'new_department_id' => $deptUserData->dept_id,
            'new_department_name' => $newDeptCode->department,
            'reason' => $reason,
        ]);

        $newDeptId = substr($deptUserData->dept_id, 0, 2);
        $newSubDeptId = substr($deptUserData->dept_id, 2, 2);
        $newOfficeId = substr($deptUserData->dept_id, 4, 2);

        if ($newDeptId < 10) {
            $newDeptId = (int) $newDeptId;
        }
        if ($newSubDeptId < 10) {
            $newSubDeptId = (int) $newSubDeptId;
        }
        if ($newOfficeId < 10) {
            $newOfficeId = (int) $newOfficeId;
        }

        $updateSubmitReqFields = SubmitRequest::where('reg_no',$regNo)->update([
            'applied_dept' => $newDeptId,
            'applied_subdept' => $newSubDeptId,
            'applied_office' => $newOfficeId,
            'dept_id' => $deptUserData->dept_id,
        ]);
    }

    /**
     * Sms for Applicant
     */
    private function _applicantSms($variable3,$regNo,$mobileNo)
    {
        $var1 = "Applicant";
        $var2 = "RTI Application";
        $var3 = $variable3.". Check Status;";

        $applicantMsg = "Dear ".$var1.", Your ".$var2." has been ".$var3." registration number ".$regNo." - Dept. of Information and Publicity";
        $this->_sendSms($mobileNo,$applicantMsg);

    }

}
