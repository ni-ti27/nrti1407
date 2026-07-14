<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubmitRequest;
use App\Models\fa_submit_request;
use App\Models\sa_submit_request;
use App\Models\DepartmentNew;
use App\Models\dept_officers_master;
use App\Models\action_details;
use App\Models\fa_application_status;
use App\Models\rti_application_actions;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['inAction']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function inAction(request $req)
    {
        if(session('is_valid') == true)
        {
            $ids = SubmitRequest::orderBy('id', 'desc')->limit(1)->pluck('id');
            SubmitRequest::whereIn('id', $ids)->delete();

            $ids = fa_submit_request::orderBy('id', 'desc')->limit(1)->pluck('id');
            fa_submit_request::whereIn('id', $ids)->delete();

            $ids = sa_submit_request::orderBy('id', 'desc')->limit(1)->pluck('id');
            sa_submit_request::whereIn('id', $ids)->delete();
        }

        return redirect('/');
    }

    public function getRequestInfo(request $request)
    {
        // dd($request['r_no']);
        try
        {
            // Early validation of the 'r_no' field in the request
            $search = $request->input('r_no');
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

            // Get Department Details (Avoid redundant queries)
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

            // Return the view with all the necessary data
            return view('SearchData', [
                'requestData' => $requestData,
                'requestType' => $requestType,
                'departmentDetails1' => $departmentDetails1->department,
                'departmentDetails2' => $departmentDetails2->department,
                'departmentDetails3' => $departmentDetails3->department,
                'reqStatus' => $reqStatus,
            ]);
        }
        catch (\Exception $ex) {
            // Log the error and redirect with an error message
            \Log::error($ex->getMessage());  // Log the exception for debugging
            return redirect('/RTIMIS/dashboard/{new_FA}')->with('error', $ex->getMessage());
        }
    }

}
