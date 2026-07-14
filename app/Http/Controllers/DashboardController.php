<?php

namespace App\Http\Controllers;

use App\Models\online_payment_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //dd($request->all());
        $start_date = isset($request->start_date) ? \Carbon\Carbon::parse($request->start_date) : "";
        $end_date = isset($request->end_date) ? \Carbon\Carbon::parse($request->end_date) : "";

        if ($request->ajax()) {
            // $result = DB::table('payment__details as opd')
            //                     ->leftjoin('submit_requests as sr', 'sr.id', '=', 'opd.applicant_id')
            //                     ->leftjoin('rti_applicant_regdata as ard', 'sr.reg_no', '=', 'ard.reg_no')
            //                     ->select('sr.id as id','sr.phone_no as phone','sr.payment_details as payment_details',
            //                         'sr.name as name','sr.email_id as email','sr.reg_no as reg_no','sr.pincode as pincode',
            //                         'opd.applicant_id as applicant_id','opd.trans_dt','opd.echallan_no as echallan_no','opd.total_amt','opd.status as status')
            //                     ->where('ard.regmobile_no',session('mobile_no'));
         $result = DB::table('payment__details as opd')
            ->leftJoin('submit_requests as sr', 'sr.reg_no', '=', 'opd.reg_no')
            ->leftJoin('rti_applicant_regdata as ard', 'ard.reg_no', '=', 'opd.reg_no')
            ->select(
                'opd.id as id', 'sr.id as srId', 'sr.phone_no as phone', 'sr.payment_details as payment_details',
                'sr.name as name', 'sr.email_id as email', 'sr.reg_no as reg_no', 'sr.pincode as pincode',
                'opd.applicant_id as applicant_id', 'opd.trans_dt', 'opd.echallan_no as echallan_no',
                'opd.total_amt', 'opd.status as status', 'opd.created_at'
            )
            ->where(function ($query) {
                $query->where('sr.mobile_no', session('mobile_no'))
                    ->orWhere('ard.regmobile_no', session('mobile_no'));
            });

          // dd($query->toSql());
            if($request->paymentMode == 'Failed'){
                $result = $result->where('opd.status','Failed');
            }
            if($request->paymentMode == 'Success'){
                $result = $result->where('opd.status','Success');
            }
            if($request->paymentMode == 'verfication_pending'){
                $result = $result->whereNull('opd.status');
            }

            if($start_date && $end_date){
                $result->whereBetween('opd.trans_dt',[$start_date->startOfDay()->format('Y-m-d H:i:s'),$end_date->endOfDay()->format('Y-m-d H:i:s')]);
            }elseif($start_date) {
                $result->whereBetween('opd.trans_dt', [$start_date->startOfDay()->format('Y-m-d H:i:s'),$start_date->endOfDay()->format('Y-m-d H:i:s')]);
            }elseif($end_date){
                $result->whereBetween('opd.trans_dt', [$end_date->startOfDay()->format('Y-m-d H:i:s'),$end_date->endOfDay()->format('Y-m-d H:i:s')]);
            }

            $result = $result->orderBy('opd.created_at', 'desc')->get();

            return Datatables::of($result)
            ->addIndexColumn()
            ->addColumn('status', function($row){
                if(!isset($row->status)){
                    $status = '<span class="text-danger">Verify</span>';;//'Payment has not been made for the given Echallan number';
                }
                elseif ($row->status == 'Failed') {
                    $status = $row->status;
                }
                else{
                    $status = $row->status;
                }
                return $status;
            })
            ->editColumn('trans_dt', function ($row)
            {
                return date('d-m-Y ', strtotime($row->trans_dt) );
            })
            ->addColumn('action', function($row){
                $actionBtn = '';
                $paymentDetails = online_payment_details::where('echallan_no',$row->echallan_no)
                                                    // ->where('status','Success')
                                                    ->latest('created_at')
                                                    ->first();

                // if(empty($paymentDetails)){
                //     $actionBtn .= '<a class=" action_btn btn btn-sm bg-indigo rounded-circle p-2 yround_btn" target="_self" id="edit_client_btn" href="'.route("payment-verify",$row->id).'">Payment Verify</a>';

                // }else{
                //     $actionBtn .= '<a class=" action_btn btn btn-sm bg-indigo rounded-circle p-2 round_btn" target="_self" id="edit_client_btn" href="#">Payment Verifed </a>';
                // }

                if(!empty($paymentDetails) && $paymentDetails->status == 'Success'){
                    $actionBtn .= '<a class="btn btn-outline-success btn-sm" tyle="font-weight-bold border" target="_self" id="edit_client_btn" href="'.route("payment-verify",$row->id).'">Payment Success</a>';
                }
                elseif (!empty($paymentDetails) && $paymentDetails->status == 'Failed') {
                    $actionBtn .= '<a class="btn btn-outline-danger btn-sm" tyle="font-weight-bold border" target="_self" id="edit_client_btn" href="'.route("payment-verify",$row->id).'">Payment Failed</a>';
                }
                else{
                    $actionBtn .= '<a class="btn btn-outline-info btn-sm " style="font-weight-bold border" target="_self" id="edit_client_btn" href="'.route("payment-verify",$row->id).'">Payment Verify</a>';
                }
                return $actionBtn;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }

        return view('payment.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
