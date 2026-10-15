<?php

namespace App\Http\Controllers;

use App\Models\online_payment_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $request->validate([
        'paymentMode' => ['nullable', 'in:Success,Failed,verfication_pending'],
        'start_date'  => ['nullable', 'date'],
        'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
        'q'           => ['nullable', 'string', 'max:100'],
        'per_page'    => ['nullable', 'in:10,25,50,100'],
    ]);

    $mobile  = session('mobile_no');
    $perPage = (int) $request->input('per_page', 10);

    // Latest payment status per eChallan number, worked out INSIDE the main query
    // (the old code ran one extra query for every row shown).
    $paymentTable = (new online_payment_details)->getTable();
    $latestStatus = online_payment_details::query()
        ->select('status')
        ->whereColumn($paymentTable . '.echallan_no', 'opd.echallan_no')
        ->latest('created_at')
        ->limit(1);

    $query = DB::table('payment__details as opd')
        ->leftJoin('submit_requests as sr', 'sr.reg_no', '=', 'opd.reg_no')
        ->select(
            'opd.id as id',
            'sr.name as name',
            'sr.reg_no as reg_no',
            'opd.echallan_no as echallan_no',
            'opd.total_amt',
            'opd.trans_dt',
            'opd.status as status',
            'opd.created_at'
        )
        ->selectSub($latestStatus, 'latest_status')
        // records of this applicant: made through a request or through registration
        ->where(function ($q) use ($mobile) {
            $q->whereIn('opd.reg_no', function ($sub) use ($mobile) {
                $sub->select('reg_no')->from('submit_requests')->where('mobile_no', $mobile);
            })->orWhereIn('opd.reg_no', function ($sub) use ($mobile) {
                $sub->select('reg_no')->from('rti_applicant_regdata')->where('regmobile_no', $mobile);
            });
        });

    // Payment mode
    if ($request->paymentMode === 'Failed' || $request->paymentMode === 'Success') {
        $query->where('opd.status', $request->paymentMode);
    } elseif ($request->paymentMode === 'verfication_pending') {
        $query->whereNull('opd.status');
    }

    // Dates (from only, to only, or both)
    if ($request->filled('start_date') || $request->filled('end_date')) {
        $from = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::parse($request->end_date);
        $to   = $request->filled('end_date')   ? Carbon::parse($request->end_date)   : Carbon::parse($request->start_date);

        $query->whereBetween('opd.trans_dt', [
            $from->startOfDay()->format('Y-m-d H:i:s'),
            $to->endOfDay()->format('Y-m-d H:i:s'),
        ]);
    }

    // Search box (replaces the DataTables search)
    if ($request->filled('q')) {
        $term = '%' . $request->q . '%';
        $query->where(function ($q) use ($term) {
            $q->where('sr.name', 'like', $term)
              ->orWhere('sr.reg_no', 'like', $term)
              ->orWhere('opd.echallan_no', 'like', $term);
        });
    }

    // The database returns only ONE page of rows (LIMIT / OFFSET)
    $payments = $query->orderByDesc('opd.created_at')
                      ->paginate($perPage)
                      ->withQueryString();

    return view('payment.dashboard', compact('payments'));
}
   /* public function index(Request $request)
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
    }*/

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
