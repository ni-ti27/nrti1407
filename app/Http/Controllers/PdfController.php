<?php

namespace App\Http\Controllers;
use App\Models\SubmitRequest;
use App\Models\Payment_Details;
use App\Models\DepartmentNew;
use App\Models\Req_dispose_of;
use App\Models\fa_submit_request;
use App\Models\FA_Dept_Mapping;
use App\Models\sa_submit_request;
use App\Models\dept_officers_master;
use App\Models\Rti_Dept_Id;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\action_details;
use App\Models\Actions;
use App\Models\sa_uploaded_documents;
use App\Models\fa_uploaded_documents;
use App\Models\Req_to_applicant;
use App\Models\rti_application_actions;
use App\Models\SA_Reply_To_Applicant;

class PdfController extends Controller
{
    public function generateDetailPdf($submitReqId)
    {
        $submitRequest = SubmitRequest::with('state', 'village', 'district', 'actionDetails')->find($submitReqId);

        $regNo = $submitRequest->reg_no;
        $slashCount = substr_count($regNo, '/');
        $orgDeptDetails = array();
        if ($slashCount == 4) {
            // It's a partial transfer, get the original reg no
            $parts = explode('/', $regNo);
            // Join first 4 parts to get original reg_no
            $originalRegNo = implode('/', array_slice($parts, 0, 4));

            // Now fetch the department of the original request
            $originalRequest = SubmitRequest::where('reg_no', $originalRegNo)->first();
            $orgDepartmentDetails1 = DepartmentNew::where('dept_code',$originalRequest->applied_dept)->where('office_code', 0)->where('suboffice_code',0)->first();
            $orgDepartmentDetails2 = DepartmentNew::where('dept_code',$originalRequest->applied_dept)->where('office_code', $originalRequest->applied_subdept)->where('suboffice_code',0)->first();
            $orgDepartmentDetails3 = DepartmentNew::where('dept_code',$originalRequest->applied_dept)->where('office_code', $originalRequest->applied_subdept)->where('suboffice_code',$originalRequest->applied_office)->first();


            $orgDeptDetails = [
            'dept' => $orgDepartmentDetails1->department,
            'subDept' => $orgDepartmentDetails2->department,
            'office' => $orgDepartmentDetails2->department,
            ];
        }
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
        if($reqestDisposeOf != null)
        {
            $officerRepliedFile  = $reqestDisposeOf['document_path'];
            $officerFile = Storage::url($officerRepliedFile);
        }
        else
            $officerFile = null;

        $requestToApplicant = Req_to_applicant::where('reg_no',$submitRequest->reg_no)->first();
        $pdf = PDF::loadView('PrintDetailPdf', [
            'orgDeptDetails' => $orgDeptDetails,
            'deptDetails' => $departmentDetails1->department,
            'officeDetails' => $departmentDetails2->department,
            'subOfficeDetails' => $departmentDetails3->department,
            'submitRequest' => $submitRequest,
            'filePath' => $filePath,
            'officerFile' => $officerFile,
            'paymentDetails' => $paymentDetails,
            'reqestDisposeOf' => $reqestDisposeOf,
            'submitReqId' => $submitReqId,
            'requestToApplicant' => $requestToApplicant
        ]);

        return $pdf->stream($submitRequest->id.'.pdf');
    }

    public function generateDetailPdfFa($faId)
    {
        if (!is_numeric($faId)) {
            return redirect()->back()->with('error', 'Invalid Request ID.');
        }
        $faSubmitRequest = fa_submit_request::with('uploadedDocuments.documentCategory','pioResponseData','state','village', 'district','FaActionDetails', 'FaActions','faRegistration')->find($faId);

        $uploadedDocuments = $faSubmitRequest->uploadedDocuments;

        //dd($uploadedDocuments);
        $filePaths = [];
        foreach ($uploadedDocuments as $document)
        {
            if ($document->document_path)
            {
                $filePath = Storage::url($document->document_path);
                $documentCategory = $document->documentCategory->document_category ?? 'First Appeal';
                $filePaths[$documentCategory] = $filePath;
            }
        }

        //dd($filePaths);
        if(!isset($filePaths))
            $filePaths = null;

        $submitRequest = SubmitRequest::where('reg_no',$faSubmitRequest->reg_no)->first();
        $reqFilePath = $submitRequest['document_path'];
        $reqFilePath = Storage::url($reqFilePath);

        /*$faDeptMappingTable = FA_Dept_Mapping::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code',$faSubmitRequest->applied_subdept)->where('suboffice_code',$faSubmitRequest->applied_office)->first();

        $fadepartmentDetails1 = DepartmentNew::where('dept_code',$faDeptMappingTable->fadept_code)->where('office_code', 0)->where('suboffice_code',0)->first();

        $fadepartmentDetails2 = DepartmentNew::where('dept_code',$faDeptMappingTable->fadept_code)->where('office_code', $faDeptMappingTable->faoffice_code)->where('suboffice_code',0)->first();

        $fadepartmentDetails3 = DepartmentNew::where('dept_code',$faDeptMappingTable->fadept_code)->where('office_code', $faDeptMappingTable->faoffice_code)->where('suboffice_code',$faDeptMappingTable->fasuboffice_code)->first();*/

        $fadepartmentDetails1 = DepartmentNew::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code', 0)->where('suboffice_code',0)->first();
        $fadepartmentDetails2 = DepartmentNew::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code', $faSubmitRequest->applied_subdept)->where('suboffice_code',0)->first();
        $fadepartmentDetails3 = DepartmentNew::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code', $faSubmitRequest->applied_subdept)->where('suboffice_code',$faSubmitRequest->applied_office)->first();


         //dd($fadepartmentDetails1,$fadepartmentDetails2,$fadepartmentDetails3);
        $faDeptDetails = [
            'dept' => $fadepartmentDetails1->department,
            'subDept' => $fadepartmentDetails2->department,
            'office' => $fadepartmentDetails3->department,
        ];

        $departmentDetails1 = DepartmentNew::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code', 0)->where('suboffice_code',0)->first();
        $departmentDetails2 = DepartmentNew::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code', $faSubmitRequest->applied_subdept)->where('suboffice_code',0)->first();
        $departmentDetails3 = DepartmentNew::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code', $faSubmitRequest->applied_subdept)->where('suboffice_code',$faSubmitRequest->applied_office)->first();

        $deptDetails = [
            'dept' => $departmentDetails1->department,
            'subDept' => $departmentDetails2->department,
            'office' => $departmentDetails3->department,
        ];

        $paymentDetails = Payment_Details::join('submit_requests as sr', 'payment__details.reg_no', '=', 'sr.reg_no')
        ->leftjoin('additional__payment_details as apd', 'sr.reg_no', '=', 'apd.reg_no')
        ->where('sr.reg_no', $faSubmitRequest->reg_no)
        ->where('payment__details.status', 'Success')
        ->select('payment__details.total_amt','payment__details.echallan_no','payment__details.created_at')
        ->get();

        $reqestDisposeOf = Req_dispose_of::where('reg_no',$faSubmitRequest->reg_no)->first();

        $pdf = PDF::loadView('PrintDetailFAPdf',[
            'faSubmitRequest' => $faSubmitRequest,
            'submitRequest' => $submitRequest,
            'paymentDetails' => $paymentDetails,
            'reqestDisposeOf' => $reqestDisposeOf,
            'deptDetails' => $deptDetails,
            'faDeptDetails' => $faDeptDetails,
            'filePaths' => $filePaths,
            'reqFilePath' => $reqFilePath,
        ]);

        return $pdf->stream($faSubmitRequest->id.'.pdf');
    }

    public function generateDetailPdfSa($saId)
    {
        if (!is_numeric($saId))
        {
            return redirect()->back()->with('error', 'Invalid Request ID.');
        }
        $saSubmitRequest = sa_submit_request::with( 'state', 'village', 'district', 'saActionDetails', 'faaResponseData')->find($saId);


        $faSubmitRequest = fa_submit_request::with(
            'uploadedDocuments.documentCategory',
            'pioResponseData',
            'state',
            'village',
            'district',
            'FaActionDetails',
            'FaActions',
            'faRegistration'
        )->where('fa_reg_no', $saSubmitRequest->fa_reg_no)
        ->first();

        $uploadedDocuments = $faSubmitRequest->uploadedDocuments;

        //dd($uploadedDocuments );
        $filePaths = [];
        foreach ($uploadedDocuments as $document)
        {
            if ($document->document_path)
            {
                $filePath = Storage::url($document->document_path);
                $documentCategory = $document->documentCategory->document_category ?? 'First Appeal';
                $filePaths[$documentCategory] = $filePath;
            }
        }

        if(!isset($filePaths))
            $filePaths = null;

        $submitRequest = SubmitRequest::where('reg_no',$faSubmitRequest->reg_no)->first();
        $reqFilePath = $submitRequest['document_path'];
        $reqFilePath = Storage::url($reqFilePath);

        /*$faDeptMappingTable = FA_Dept_Mapping::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code',$faSubmitRequest->applied_subdept)->where('suboffice_code',$faSubmitRequest->applied_office)->first();

        $fadepartmentDetails1 = DepartmentNew::where('dept_code',$faDeptMappingTable->fadept_code)->where('office_code', 0)->where('suboffice_code',0)->first();

        $fadepartmentDetails2 = DepartmentNew::where('dept_code',$faDeptMappingTable->fadept_code)->where('office_code', $faDeptMappingTable->faoffice_code)->where('suboffice_code',0)->first();

        $fadepartmentDetails3 = DepartmentNew::where('dept_code',$faDeptMappingTable->fadept_code)->where('office_code', $faDeptMappingTable->faoffice_code)->where('suboffice_code',$faDeptMappingTable->fasuboffice_code)->first();*/

        $fadepartmentDetails1 = DepartmentNew::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code', 0)->where('suboffice_code',0)->first();
        $fadepartmentDetails2 = DepartmentNew::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code', $faSubmitRequest->applied_subdept)->where('suboffice_code',0)->first();
        $fadepartmentDetails3 = DepartmentNew::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code', $faSubmitRequest->applied_subdept)->where('suboffice_code',$faSubmitRequest->applied_office)->first();

        // dd($fadepartmentDetails1,$fadepartmentDetails2,$fadepartmentDetails3);
        $faDeptDetails = [
            'dept' => $fadepartmentDetails1->department,
            'subDept' => $fadepartmentDetails2->department,
            'office' => $fadepartmentDetails3->department,
        ];

        $departmentDetails1 = DepartmentNew::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code', 0)->where('suboffice_code',0)->first();
        $departmentDetails2 = DepartmentNew::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code', $faSubmitRequest->applied_subdept)->where('suboffice_code',0)->first();
        $departmentDetails3 = DepartmentNew::where('dept_code',$faSubmitRequest->applied_dept)->where('office_code', $faSubmitRequest->applied_subdept)->where('suboffice_code',$faSubmitRequest->applied_office)->first();

        $deptDetails = [
            'dept' => $departmentDetails1->department,
            'subDept' => $departmentDetails2->department,
            'office' => $departmentDetails3->department,
        ];

        $paymentDetails = Payment_Details::join('submit_requests as sr', 'payment__details.reg_no', '=', 'sr.reg_no')
        ->leftjoin('additional__payment_details as apd', 'sr.reg_no', '=', 'apd.reg_no')
        ->where('sr.reg_no', $faSubmitRequest->reg_no)
        ->where('payment__details.status', 'Success')
        ->select('payment__details.total_amt','payment__details.echallan_no','payment__details.created_at')
        ->get();

        $reqestDisposeOf = Req_dispose_of::where('reg_no',$faSubmitRequest->reg_no)->first();

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

            $saDeptDetails = [
                'dept' => $departmentDetails1,
                'subDept' => $departmentDetails2,
                'office' => $departmentDetails3,
            ];

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

        $statusId = $saSubmitRequest->saActionDetails->first()->status_id;
        $SA_Reply_To_Applicant = null;

        if ($statusId == 15) {
            $SA_Reply_To_Applicant = SA_Reply_To_Applicant::where('sa_reg_no', $saSubmitRequest->sa_reg_no)
                                        ->latest('created_at')
                                        ->first(['reason', 'created_at']);
        }
        $pdf = PDF::loadView('PrintDetailSAPdf',[
            'faSubmitRequest' => $faSubmitRequest,
            'submitRequest' => $submitRequest,
            'paymentDetails' => $paymentDetails,
            'reqestDisposeOf' => $reqestDisposeOf,
            'deptDetails' => $deptDetails,
            'faDeptDetails' => $faDeptDetails,
            'saDeptDetails'=> $saDeptDetails,
            'filePaths' => $filePaths,
            'reqFilePath' => $reqFilePath,
            'saSubmitRequest' => $saSubmitRequest,
            'SA_Reply_To_Applicant' => $SA_Reply_To_Applicant,
            'saFiles' => $saFiles,
        ]);

        return $pdf->stream($saSubmitRequest->id.'.pdf');

    }
    /**
     * Generate Notice Function
     */
    public function generateNotice(Request $request)
    {
        try
        {
            $caseId = $request->input('case_id');
            $pioUser = $request->input('pio_user');
            $faaUser = $request->input('faa_user');
            $pioAddress = $request->input('pio_address');
            $applicantAddress = $request->input('applicant_address');

            $currentuser = session('username');
            $currentOfficer = dept_officers_master::where('username', $currentuser)
            ->where('is_active', 1)->first();

            if(!isset($pioAddress))
                $pioAddress = null;

            if(!isset($applicantAddress))
                $applicantAddress = null;

            $saDetails = sa_submit_request::with('saActionDetails')->find($caseId);

            $saActionDetails = $saDetails->saActionDetails[0];

            $officerData = dept_officers_master::whereIn('id',[$saActionDetails->assigned_off1, $saActionDetails->assigned_off2, $pioUser, $faaUser])->get();
            $clerk = $officerData->where('role',5)->first();
            $sic = $officerData->where('role',6)->first();
            $pio = $officerData->where('role',2)->first();
            $faa = $officerData->where('role',4)->first();
            // dd($officerData[1]);

            if(!isset($pio))
                throw new exception('PIO user details not present');

            if(!isset($sic))
                throw new exception('SIC user details not present');

            $dateNow = Carbon::now()->format('Y-m-d H:i');

            $submitRequestObj = new SubmitRequest;
            $deptCode = $submitRequestObj->createDeptCode($saDetails['applied_dept'], $saDetails['applied_subdept'], $saDetails['applied_office']);
            $deptDetails = Rti_Dept_Id::where('dept_id', $deptCode)->first();

            $folderPath = 'public/Second_Appeal_Documents/Notice/' . substr($deptDetails->dept_id, 0, 2);
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
            }

            // dd($applicantAddress,$pioAddress);

            $pdf = PDF::loadView('NoticePdf', [
                'saDetails' => $saDetails,
                'clerk' => $clerk,
                'sic' => $sic,
                'pio' => $pio,
                'faa' => $faa,
                'dateNow' => $dateNow,
                'currentOfficer' => $currentOfficer,
                'pioAddress' => $pioAddress,
                'applicantAddress' => $applicantAddress,
            ]);

            $pdfContent = $pdf->output();
            $fileName = 'Second-Appeal-Notice' . $saDetails->id . '.pdf';

           /* $path = Storage::put($folderPath . '/' . $fileName, $pdfContent);
            $pdfUrl = Storage::url($folderPath.'/'.$fileName);
            Session::put('noticePath',$pdfUrl);*/
            $relativePath = $folderPath . '/' . $fileName;
            Storage::put($relativePath, $pdfContent);
            $pdfUrl = Storage::url($relativePath);
            Session::put('noticePath', $relativePath);

            return response()->json([
                'pdfUrl' => $pdfUrl
            ]);
        }
        catch(Exception $ex)
        {
            return back()->with('warning', $ex->getMessage());
        }

    }

    public function searchDataPdf($reg_no)
    {

     // Early validation of the 'r_no' field in the request
            $search = base64_decode($reg_no);
            if (!$search) {
                throw new \Exception('Registration Number issue');
            }

            // Initialize variables
            $requestData = null;
            $requestType = null;
            $deptOfficial = null;

            // Search for SubmitRequest OR FA SubmitRequest

            if(strpos($search,'NR'))
            {
                // dd('NR req');
                $requestData = SubmitRequest::from('submit_requests as sr')
                ->join('action_details', 'sr.reg_no', '=', 'action_details.reg_no')
                ->join('application_status', 'action_details.status_id', '=', 'application_status.id')
                ->join('dept_officers_master','action_details.assigned_off2', '=' , 'dept_officers_master.id')
                ->select('sr.reg_no','sr.applied_dept','sr.applied_subdept','sr.applied_office','sr.dept_id','sr.created_at','sr.updated_at','sr.name','sr.address_line1','sr.address_line2','sr.address_line3','sr.mobile_no','sr.email_id','sr.pincode','application_status.status','dept_officers_master.username', 'dept_officers_master.name as deptusername')
                ->where('sr.reg_no', 'like', "%{$search}%")->first();

                $requestType = 'New Request';
            }
            else if(strpos($search,'FA'))
            {
                // dd('FA Req');
                $requestData = fa_submit_request::from('fa_submit_requests as fsr')
                ->join('fa_application_statuses as fas', 'fsr.fa_reg_no', '=', 'fas.fa_reg_no')
                ->join('application_status as as2', 'fas.status_id', '=', 'as2.id')
                ->join('dept_officers_master as dom','fas.assigned_off2', '=' , 'dom.id')
                ->select('fsr.id','fsr.address_line1','fsr.address_line2','fsr.address_line3','fsr.mobile_no','fsr.email_id','fsr.pincode','fsr.applied_dept','fsr.applied_subdept','fsr.applied_office','fsr.dept_id','fsr.name','fsr.reg_no','fsr.fa_reg_no','fsr.created_at','as2.status','dom.name as deptusername','dom.username','fas.updated_at')
                ->where('fsr.fa_reg_no', 'like', "%{$search}%")->first();

                $requestType = 'First Appeal Request';
            }

            $reqStatus = rti_application_actions::where('reg_no', 'LIKE', '%' . $search . '%')
            ->orderBy('date_of_efiling', 'desc')->orderBy('app_history_created', 'desc')->get();

            // If requestData is null, redirect or show an error
            if (!$requestData) {
                throw new \Exception('No request data found for the provided registration number');
            }

            // Get Department Details
            $departmentDetails1 = DepartmentNew::where('dept_code', $requestData->applied_dept)
                ->where('office_code', 0)
                ->where('suboffice_code', 0)
                ->first();

            $departmentDetails2 = DepartmentNew::where('dept_code', $requestData->applied_dept)
                ->where('office_code', $requestData->applied_subdept)
                ->where('suboffice_code', 0)
                ->first();

            $departmentDetails3 = DepartmentNew::where('dept_code', $requestData->applied_dept)
                ->where('office_code', $requestData->applied_subdept)
                ->where('suboffice_code', $requestData->applied_office)
                ->first();

            // Check for missing department details
            if (!$departmentDetails1 || !$departmentDetails2 || !$departmentDetails3) {
                throw new \Exception('Some department details are missing');
            }


        $pdf = PDF::loadView('SearchDataPdf',[
                'requestData' => $requestData,
                'requestType' => $requestType,
                'departmentDetails1' => $departmentDetails1->department,
                'departmentDetails2' => $departmentDetails2->department,
                'departmentDetails3' => $departmentDetails3->department,
                'reqStatus' => $reqStatus,
        ]);

        $filename = str_replace(['/', '\\'], '_', $search) . '.pdf';
        return $pdf->stream($filename);

    }
}
