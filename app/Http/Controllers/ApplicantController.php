<?php

namespace App\Http\Controllers;
use App\Models\SubmitRequest;
use App\Models\Payment_Details;
use App\Models\DepartmentNew;
use App\Models\Req_dispose_of;
use App\Models\dept_officers_master;
use App\Models\fa_application_status;
use App\Models\fa_submit_request;
use App\Models\sa_submit_request;
use App\Models\fa_uploaded_documents;
use App\Models\sa_uploaded_documents;
use App\Models\pioResponse;
use App\Models\Village;
use App\Models\District;
use App\Models\Role;
use App\Models\Role_Action;
use App\Models\rtiStatusFaReq;
use App\Models\rtiStatusSaReq;
use App\Models\action_details;
use App\Models\Actions;
use Illuminate\Support\Facades\Storage;
use App\Models\Req_to_applicant;
use App\Models\FaActions;
use App\Models\SA_Reply_To_Applicant;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function detailedInfo($submitReqId)
    {
        $submitRequest = SubmitRequest::with('state', 'village', 'district', 'actionDetails')->find($submitReqId);

        $departmentDetails1 = DepartmentNew::where('dept_code',$submitRequest->applied_dept)->where('office_code', 0)->where('suboffice_code',0)->first();
        $departmentDetails2 = DepartmentNew::where('dept_code',$submitRequest->applied_dept)->where('office_code', $submitRequest->applied_subdept)->where('suboffice_code',0)->first();
        $departmentDetails3 = DepartmentNew::where('dept_code',$submitRequest->applied_dept)->where('office_code', $submitRequest->applied_subdept)->where('suboffice_code',$submitRequest->applied_office)->first();

        $filePath  = $submitRequest['document_path'];
        $filePath = Storage::url($filePath);

        $paymentDetails = Payment_Details::join('submit_requests as sr', 'payment__details.reg_no', '=', 'sr.reg_no')
        ->leftjoin('additional__payment_details as apd', 'sr.reg_no', '=', 'apd.reg_no')
        ->where('sr.reg_no', $submitRequest->reg_no)
        ->where('payment__details.status', 'Success')
        ->select('payment__details.total_amt','payment__details.echallan_no','payment__details.created_at')
        ->get();

        $reqestDisposeOf = Req_dispose_of::where('reg_no',$submitRequest->reg_no)->first();
        $requestToApplicant = Req_to_applicant::where('reg_no',$submitRequest->reg_no)->first();
        // dd($reqestDisposeOf);
        return view('detailedRequest', [
            'deptDetails' => $departmentDetails1->department,
            'officeDetails' => $departmentDetails2->department,
            'subOfficeDetails' => $departmentDetails3->department,
            'submitRequest' => $submitRequest,
            'filePath' => $filePath,
            'paymentDetails' => $paymentDetails,
            'reqestDisposeOf' => $reqestDisposeOf,
            'requestToApplicant' => $requestToApplicant,
            'submitReqId' => $submitReqId
        ]);
    }


    public function faDetailedView($id)
    {
        try {

        $faDetails = fa_submit_request::from('fa_submit_requests as fsr')
            ->join('fa_application_statuses as fas', 'fsr.fa_reg_no', '=', 'fas.fa_reg_no')
            ->join('submit_requests as sr', 'fsr.reg_no', '=', 'sr.reg_no')
            ->leftJoin('states', 'fsr.state', '=', 'states.id')
            ->select('fsr.*', 'fas.status_id', 'states.name as stateName', 'sr.reg_no', 'sr.created_at as submitted_date', 'fsr.requested_note', 'sr.document_path', 'sr.document_name_original', 'sr.request_text as sr_req_note', 'fsr.requested_note as fa_req_note', 'fsr.pioresponse', 'sr.village', 'sr.district', 'sr.mobile_no', 'sr.phone_no')
            ->where('fsr.id', $id)
            ->first();

        if ($faDetails == null) {
            throw new Exception('Data Missing (Either First Appeal or New Request Data Missing)');
        }

        // Fetch related details
        $villageFetch = Village::where('id', $faDetails->village)->first();
        $districtFetch = District::where('id', $faDetails->district)->first();
        $pioResponse = pioResponse::where('id', $faDetails->pioresponse)->first();
        $uploadedDocs = fa_uploaded_documents::where('reg_no', $faDetails['reg_no'])->get();
        $pioReplyDoc = $uploadedDocs->where('document_category', '7')->first();
        $supportingDoc = $uploadedDocs->where('document_category', '2')->first();
        $submitReq = SubmitRequest::where('reg_no', $faDetails['reg_no'])->first();

        // Department details
        $departmentDetails1 = DepartmentNew::where('dept_code', $submitReq->applied_dept)->where('office_code', 0)->where('suboffice_code', 0)->first();
        $departmentDetails2 = DepartmentNew::where('dept_code', $submitReq->applied_dept)->where('office_code', $submitReq->applied_subdept)->where('suboffice_code', 0)->first();
        $departmentDetails3 = DepartmentNew::where('dept_code', $submitReq->applied_dept)->where('office_code', $submitReq->applied_subdept)->where('suboffice_code', $submitReq->applied_office)->first();

        $departmentDetails = [
            'dept' => $departmentDetails1->department,
            'office' => $departmentDetails2->department,
            'suboffice' => $departmentDetails3->department,
        ];

        // Action IDs based on status
        $actionIdtoUse = ($faDetails->status_id == 1) ? [10, 13, 14] : [11, 12]; // New request or in process action IDs

        // Prepare uploaded documents data
        $uploadedDocs = [
            'suppDoc' => $supportingDoc ?? null,
            'pioReplyDoc' => $pioReplyDoc ?? null,
            'details' => $pioResponse,
            'village' => $villageFetch ? $villageFetch->name : 'Other',
            'district' => $districtFetch ? $districtFetch->name : 'Other',
        ];

        $faAction = FaActions::where('fa_reg_no', $faDetails->fa_reg_no)
        ->latest('created_at')
        ->first();

        if ($faAction && !empty($faAction->document_path)) {

            $cleanPath = preg_replace('/^(public\/|storage\/)/', '', $faAction->document_path);

            $faAction->document_path = 'storage/'.$cleanPath;
        }

        return view('faDetailedView', compact('faDetails', 'submitReq', 'departmentDetails', 'uploadedDocs', 'faAction'));
        }
        catch (Exception $ex)
        {
            return redirect('faDetailedView')->with('warning', $ex->getMessage());
        }

    }

    public function saDetailedView($id)
    {
        try {
            // Fetch saSubmitRequest and related data
            $saSubmitRequest = sa_submit_request::with( 'state', 'village', 'district', 'saActionDetails', 'faaResponseData')->find($id);

            // Department details
            $departmentDetails1 = DepartmentNew::where('dept_code', $saSubmitRequest->applied_dept)
                ->where('office_code', 0)
                ->where('suboffice_code', 0)
                ->first();

            $departmentDetails2 = DepartmentNew::where('dept_code', $saSubmitRequest->applied_dept)
                ->where('office_code', $saSubmitRequest->applied_subdept)
                ->where('suboffice_code', 0)
                ->first();

            $departmentDetails3 = DepartmentNew::where('dept_code', $saSubmitRequest->applied_dept)
                ->where('office_code', $saSubmitRequest->applied_subdept)
                ->where('suboffice_code', $saSubmitRequest->applied_office)
                ->first();

            $fullDeptData = [
                'department' => $departmentDetails1,
                'subDepartment' => $departmentDetails2,
                'officeDepartment' => $departmentDetails3,
            ];

            // Action details and assigned officer data
            $statusId = $saSubmitRequest->saActionDetails->first()->status_id;
            $assignedOfficers = action_details::where('reg_no', $saSubmitRequest->reg_no)->first();
            $assignedOfficerData = isset($assignedOfficers['assigned_off1'])
                ? dept_officers_master::where('id', $assignedOfficers['assigned_off2'])->where('is_active', 1)->first()
                : null;

            // Action IDs based on status
            $idsAction = ($statusId == 11) ? [19, 20] : [21, 22, 23];
            $actions = ($statusId == 11) ? Actions::whereIn('id', $idsAction)->orderBy('id')->get() : null;

            // Officer details
            $clerkOfficers = dept_officers_master::where('is_active', 1)->where('role', 5)->first();
            $sicOfficers = dept_officers_master::where('is_active', 1)->where('role', 6)->first();

            $officersDetails = [
                'clerk' => $clerkOfficers,
                'sic' => $sicOfficers,
            ];

            // Handle uploaded files
            $getSAfiles = sa_uploaded_documents::where('sa_reg_no', $saSubmitRequest->sa_reg_no)->get();
            $saFiles = [];
            $requestedText = [];

            if (!$getSAfiles->isEmpty()) {
                foreach ($getSAfiles as $sa) {
                    $file = [
                        'document_category' => $sa['document_category'],
                        'document_name' => $sa['doc_name'],
                        'document_path' => Storage::url($sa['doc_path']),
                        'document_name_original' => $sa['doc_original_name'],
                        'meta_data' => $sa['meta_data'],
                    ];

                    switch ($file['document_category']) {
                        case 6:
                            $saFiles['idProof'] = $file;
                            break;
                        case 4:
                        case 5:
                            $saFiles['supporting_doc'][] = $file;
                            break;
                        case 3:
                            $saFiles['saFile'] = $file;
                            break;
                        case 1:
                            $saFiles['newRequestFile'] = $file;
                            $requestedText['rti_req'] = json_decode($file['meta_data'], true);
                            break;
                        case 2:
                            $saFiles['faRequestFile'] = $file;
                            $requestedText['faa_req'] = json_decode($file['meta_data'], true);
                            break;
                    }
                }
            }

            // Additional request details if it's an online request
            $requestDetails = [];
            $faRequestDetails = [];

            if ($saSubmitRequest->online_request) {
                $requestDetails = SubmitRequest::where('reg_no', $saSubmitRequest->reg_no)->first();
                $requestDetails['filepath'] = Storage::url($requestDetails['document_path']);
                $requestedText['rti_req'] = $requestDetails['request_text'];

                $saFiles['newRequestFile'] = [
                    "document_category" => 1,
                    "document_name" => $requestDetails['document_name'],
                    "document_path" => $requestDetails['filepath'],
                    "document_name_original" => $requestDetails['document_name_original'],
                ];

                $faRequestDetails = fa_submit_request::with('pioResponseData')->where('reg_no', $saSubmitRequest->reg_no)->first();
                $faComplainResp = $faRequestDetails->pioResponseData;
                $faRequestDetails['faResponseReason'] = $faComplainResp->reason;
                $requestedText['faa_req'] = $faRequestDetails['requested_note'];

                $getFAfiles = fa_uploaded_documents::where('reg_no', $saSubmitRequest->reg_no)
                    ->where('document_category', 2)
                    ->first();

                    if (!empty($getFAfiles) && isset($getFAfiles['document_name'], $getFAfiles['document_path'], $getFAfiles['document_name_original'])) {
                    $saFiles['faRequestFile'] = [
                        "document_category" => 2,
                        "document_name" => $getFAfiles['document_name'],
                        "document_path" => Storage::url($getFAfiles['document_path']),
                        "document_name_original" => $getFAfiles['document_name_original'],
                    ];
                }
            }

            $SA_Reply_To_Applicant = null;

            if ($statusId == 15) {
                $SA_Reply_To_Applicant = SA_Reply_To_Applicant::where('sa_reg_no', $saSubmitRequest->sa_reg_no)
                                            ->latest('created_at')
                                            ->first(['reason', 'created_at']);
            }


            return view('saDetailedView', [
                'datajs' => [$saSubmitRequest->applied_dept, $saSubmitRequest->applied_subdept],
                'deptDetails' => $departmentDetails1->department,
                'officeDetails' => $departmentDetails2->department,
                'subOfficeDetails' => $departmentDetails3->department,
                'saSubmitRequest' => $saSubmitRequest,
                'assignedOfficerData' => $assignedOfficerData,
                'officersDetails' => $officersDetails,
                'fullDeptData' => $fullDeptData,
                'requestedText' => $requestedText,
                'SA_Reply_To_Applicant' => $SA_Reply_To_Applicant,
                'saFiles' => $saFiles,
            ]);
        } catch (Exception $ex) {
            return back()->with('warning', $ex->getMessage());
        }

    }
}
