<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Actions;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportsController extends Controller
{
    public function getActions(Request $request)
    {
        $username = session('username');
        $role     = session('role');
        $deptId   = (string)session('deptId');
        $selectedActionName = $request->get('action_name');

        $deptOfficer = $this->getOfficer($username);
        $availableTypes = $this->getAvailableTypes($role);
        $actions = Actions::whereIn('action_type', $availableTypes)
            ->orderByRaw("
            CASE
                WHEN action_type = 'request' THEN 0
                WHEN action_type = 'fa_request' THEN 1
                WHEN action_type = 'sa_request' THEN 2
            END
        ")
        ->orderBy('sort_order')->get();

        $currentAction = Actions::where('name', $selectedActionName)->first();
        $actionType    = $currentAction->action_type ?? null;

        $requestLines = null;

        if ($request->filled(['action_name', 'start_date', 'end_date']) && $actionType) {
            $requestLines = $this->executeReportQuery($request, $actionType, $selectedActionName, $deptId, $deptOfficer);
        }

        return view('Reports.Actions', compact('actions', 'requestLines', 'selectedActionName', 'actionType', 'deptId'));
    }

    private function getOfficer($username)
    {
        return DB::table('public.dept_officers_master')
            ->where('username', $username)
            ->where('is_active', 1)
            ->first();
    }

    private function getAvailableTypes($role): array
    {
        return match (true) {
            $role == 1              => ['request', 'fa_request'],
            $role < 4               => ['request'],
            $role == 4              => ['fa_request'],
            in_array($role, [5, 6]) => ['sa_request'],
            default                 => [],
        };
    }

    private function executeReportQuery($request, $actionType, $selectedActionName, $deptId, $deptOfficer)
    {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate   = Carbon::parse($request->end_date)->endOfDay();
        $officerId = $deptOfficer->id ?? null;

        $tableAlias = match ($actionType) {
            'fa_request' => 'fa',
            'sa_request' => 'sa',
            default      => 'n',
        };

        // 1. Initialize Schema
        $query = $this->initializeQuerySchema($actionType, $tableAlias);

        // 2. Apply Filters
        $this->applyActionFilters($query, $actionType, $selectedActionName);
        $checkbox = $request->query('all_dept');
        if ($deptId === '430000' && $checkbox == '1') {
            $query->where("{$tableAlias}.state", '6');
        } else {
            $this->applySecurityFilters($query, $actionType, $tableAlias, $officerId, $selectedActionName, $deptOfficer, $deptId);
        }

        // Filter by the date the when application was filed
        $query->whereBetween("ad.created_at", [$startDate, $endDate]);

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $fullSql = vsprintf(
            str_replace('?', '%s', $sql),
            array_map(function ($binding) {
                if (is_null($binding)) return 'NULL';
                if (is_bool($binding)) return $binding ? '1' : '0';
                if ($binding instanceof \DateTime) return "'" . $binding->format('Y-m-d H:i:s') . "'";
                return is_numeric($binding) ? $binding : "'" . addslashes($binding) . "'";
            }, $bindings)
        );

       //dd($fullSql);

        // 3. Pagination Logic
        // We count ad.id because one request can have multiple action records
        $countQuery = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query);

        $totalCount = $countQuery->count();

        $currentPage = $request->get('page', 1);
        $perPage = 10;

        $results = $query->orderBy("{$tableAlias}.created_at", 'desc')
            ->offset(($currentPage - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return new LengthAwarePaginator(
            $results,
            $totalCount,
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => $request->query()]
        );
    }

   private function initializeQuerySchema($actionType, $tableAlias)
    {
        return match ($actionType) {
            'fa_request' => DB::table('public.fa_submit_requests as fa')
                ->join('public.fa_action_details as ad', 'fa.fa_reg_no', '=', 'ad.fa_reg_no')
                ->leftJoin('public.fa_application_statuses as fas', 'fa.fa_reg_no', '=', 'fas.fa_reg_no')
                ->join('application_status as as_status', 'fas.status_id', '=', 'as_status.id')
                ->leftJoin('public.dept_master as dm', function ($join) use ($tableAlias) {
                    $join->on(DB::raw("CAST({$tableAlias}.applied_dept AS TEXT)"), '=', DB::raw("CAST(dm.dept_code AS TEXT)"))
                        ->where('dm.office_code', 0)
                        ->where('dm.suboffice_code', 0);
                })
                ->selectRaw('DISTINCT ON (fa.fa_reg_no)
                    ad.id as action_id,
                    fa.name as applicant_name,
                    fa.mobile_no,
                    fa.reg_no,
                    fa.fa_reg_no,
                    ad.changes,
                    ad.created_at,
                    dm.department as department_name,
                    as_status.status')
                ->orderBy('fa.fa_reg_no')
                ->orderBy('ad.created_at', 'desc'),

            'sa_request' => DB::table('public.sa_submit_requests as sa')
                ->join('public.sa_application_history as ad', 'sa.sa_reg_no', '=', 'ad.sa_reg_no')
                ->leftJoin('actions as act_master', 'act_master.id', '=', 'ad.changes_id')
                ->leftJoin('sa_action_details as sad', 'sa.sa_reg_no', '=', 'sad.reg_no')
                ->leftJoin('application_status as as_status', 'as_status.id', '=', 'sad.status_id')
                ->leftJoin('public.dept_master as dm', function ($join) use ($tableAlias) {
                    $join->on(DB::raw("CAST({$tableAlias}.applied_dept AS TEXT)"), '=', DB::raw("CAST(dm.dept_code AS TEXT)"))
                        ->where('dm.office_code', 0)
                        ->where('dm.suboffice_code', 0);
                })
                ->selectRaw('DISTINCT ON (sa.sa_reg_no)
                    ad.id as action_id,
                    sa.name as applicant_name,
                    sa.mobile_no,
                    sa.reg_no,
                    sa.sa_reg_no,
                    act_master.name as changes,
                    ad.created_at,
                    dm.department as department_name,
                    as_status.status')
                ->orderBy('sa.sa_reg_no')
                ->orderBy('ad.created_at', 'desc'),

            default => DB::table('public.submit_requests as n')
                ->join('public.application_history as ad', 'n.reg_no', '=', 'ad.reg_no')
                ->leftJoin('public.action_details as act', 'n.reg_no', '=', 'act.reg_no')
                ->join('application_status as as_status', 'act.status_id', '=', 'as_status.id')
                ->leftJoin('public.dept_master as dm', function ($join) use ($tableAlias) {
                    $join->on(DB::raw("CAST({$tableAlias}.applied_dept AS TEXT)"), '=', DB::raw("CAST(dm.dept_code AS TEXT)"))
                        ->where('dm.office_code', 0)
                        ->where('dm.suboffice_code', 0);
                })
                ->selectRaw("
                    DISTINCT ON (n.reg_no)
                    ad.id as action_id,
                    n.name as applicant_name,
                    n.mobile_no,
                    n.reg_no,
                    ad.changes,
                    ad.created_at,
                    dm.department as department_name,
                    as_status.status
                ")
                ->orderBy('n.reg_no')
                ->orderBy('ad.created_at', 'desc')
        };
    }

    private function applyActionFilters($query, $actionType, $selectedActionName)
    {
        $statusMap = [
            "SCRUTINY COMPLETED"                => "Scrutiny Completed",
            "ISSUE NOTICE TO PARTIES"           => "Notice to Parties",
            "RECORD PROCEEDINGS"                => "Proceedings in Progress",
            "REVERT SECOND APPEAL TO APPLICANT" => "Reverted to Applicant",
            "FINAL JUDGEMENT ON APPEAL"         => "Disposed"
        ];
        // Use ILIKE for PostgreSQL case-insensitive matching
        if ($actionType === 'sa_request') {
            $query->where('act_master.name', 'ILIKE', "%{$selectedActionName}%")->orWhere('as_status.status',$statusMap[$selectedActionName]);
        } else {
            if ($selectedActionName === 'RTI APPLICATION SUBMITTED') {
                $query->where(function ($q) use ($selectedActionName) {
                    $q->where('ad.changes', 'ILIKE', "%{$selectedActionName}%");
                   // ->orWhere('ad.changes', 'ILIKE', '%NEW ENTRY CREATED; PLEASE VERIFY AND UPDATE/PAY RTI FEES%');
                });
            }else if ($selectedActionName === 'PARTIAL TRANSFER OF REQUEST TO ANOTHER AUTHORITY') {
                $searchTerm = 'PARTIAL TRANSFER OF SUBMITTED APPLICATION';
                $query->where('ad.changes', 'ILIKE', "%{$searchTerm}%");
            }
            else if ($selectedActionName === 'RTI FIRST APPEAL SUBMITTED') {
                $searchTerm = 'FIRST APPEAL NEW REQUEST SUBMITTED';
                $query->where('ad.changes', 'ILIKE', "%{$searchTerm}%");
            } else {
                $query->where('ad.changes', 'ILIKE', "%{$selectedActionName}%");
            }
        }
    }

    private function applySecurityFilters($query, $actionType, $tableAlias, $officerId, $selectedActionName, $deptOfficer, $deptId)
    {
        if ($actionType === 'fa_request') {
            $query->where(function ($q) use ($officerId, $selectedActionName) {
                    // Standard conditions for First Appeal requests
                    $q->where("fas.assigned_off1", $officerId)
                    ->orWhere("fas.assigned_off2", $officerId);

                    // Add the extra condition ONLY for this specific action
                    if ($selectedActionName === 'FORWARD THE FIRST APPEAL TO FIRST APPELLATE AUTHORITY') {
                        $q->orWhere("fas.updated_by", $officerId);
                    }
                });
        } else if ($actionType === 'sa_request') {
            $query->where(function ($q) use ($officerId) {
                $q->where("sad.assigned_off1", $officerId)->orWhere("sad.assigned_off2", $officerId);
            });
        } else {

                $query->where(function ($q) use ($deptId, $officerId, $selectedActionName, $deptOfficer) {

                // =====================================================
                // 1. DEPARTMENT-BASED CASES
                // =====================================================

                $isSpecial = in_array($selectedActionName, [
                        'TRANSFER REQUEST TO OTHER PUBLIC AUTHORITY',
                        'FORWARD REQUEST TO CONCERNED PIO',
                        'FORWARD REQUEST TO CONCERNED APIO'
                ]);

                if($isSpecial)
                {
                    $q->whereIn('ad.reg_no', function ($sub) use ($deptId) {
                        $sub->select('reg_no')
                            ->from('public.application_history')
                            ->where('current_dept_id', $deptId);
                    });
                }

                // =====================================================
                // 2. OFFICER-BASED CASES
                // =====================================================
                $q->orWhere(function ($sub) use ($officerId, $selectedActionName, $deptOfficer , $isSpecial) {


                    if ($isSpecial) {
                        // For transfer/special cases, include OR conditions for all relevant fields
                        $sub->where(function ($s) use ($officerId, $deptOfficer) {

                            // assigned_off1 only for role 1 officer
                            if ($deptOfficer && $deptOfficer->role == 1) {
                                $s->orWhere('act.assigned_off1', $officerId);
                            }

                            // always check updated_by and assigned_off2
                            $s->orWhere('act.updated_by', $officerId)
                            ->orWhere('act.assigned_off2', $officerId);
                        });
                    } else {
                        // Non-special cases: assigned_off1 OR assigned_off2
                        $sub->where(function ($s) use ($officerId) {
                            $s->orWhere('act.assigned_off1', $officerId)
                            ->orWhere('act.assigned_off2', $officerId);
                        });
                    }
                });

            });
        }
    }
}