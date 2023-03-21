<?php

namespace App\Http\Controllers;

use App\User;
use App\Branch;
use App\Opportunity;
use App\OpportunityTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\DateRangeRequest;

class ReportsController extends Controller
{

    public function requestsByCountry(DateRangeRequest $request)
    {
        try {
            $from = $request->from;
            $to = $request->to;

            $data =  Opportunity::leftJoin('branches as branch', 'branch.id', '=', 'opportunities.branch_id')
                ->select('branch.name as country_name')
                ->addSelect(DB::raw('count(*) as total'))
                ->where('submission_date', '>=', $from)
                ->where('submission_date', '<=', $to)
                ->groupBy('country_name')
                ->orderBy('total', 'desc')
                ->get();

            return $data;
            // return response([
            //     'data' => $data,
            //     'message' => "",
            //     'errors' => []
            // ], 200);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function requestsDistributionByCountry(DateRangeRequest $request)
    {
        try {

            $from = $request->from;
            $to = $request->to;

            $data =  Opportunity::leftJoin('branches as branch', 'branch.id', '=', 'opportunities.branch_id')
                ->select('branch.name as country_name')
                ->where('opportunities.submission_date', '>=', $from)
                ->where('opportunities.submission_date', '<=', $to)
                ->addSelect(DB::raw('count(*) as total'))
                ->addSelect(DB::raw('IFNULL(SUM(opportunities.status = "won"),0) as won'))
                ->addSelect(DB::raw('IFNULL(SUM(opportunities.status = "lost"),0) as lost'))
                ->addSelect(DB::raw('IFNULL(SUM(opportunities.status = "cancelled"),0) as cancelled'))
                ->addSelect(DB::raw('IFNULL(SUM(opportunities.category = "proposal"),0) as proposal'))
                ->addSelect(DB::raw('IFNULL(SUM(opportunities.category = "rfp writing"),0) as rfp_writing'))
                ->addSelect(DB::raw('IFNULL(SUM(opportunities.category = "presentation"),0) as presentation'))
                ->addSelect(DB::raw('IFNULL(SUM(opportunities.category = "demo"),0) as demo'))

                // ->addSelect('IFNULL(SUM(opportunities.rfp_status = "inprogress"),0) as inprogress')
                ->groupBy('country_name')
                ->orderBy('total', 'desc')
                ->get();

            return $data;
            // return response([
            //     'data' => $data,
            //     'message' => "",
            //     'errors' => []
            // ], 200);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function requestsStatusByResources(DateRangeRequest $request)
    {
        try {

            $from = $request->from;
            $to = $request->to;

            // $data =  OpportunityTask::
            //     join('users as user', 'user.id', '=', 'opportunity_tasks.assigned_to')
            //     ->leftJoin('opportunities as opps', 'opportunity_tasks.opportunity_id', '=', 'opps.id')
            //     ->where('opps.submission_date' ,'>=' , $from)
            //     ->where('opps.submission_date' ,'<=' , $to)
            //     ->where('user.id', '!=', null)
            //     ->select('user.name as user_name','user.id as user_id')
            //     ->addSelect(DB::raw('count(*) as total'))
            //     ->addSelect(DB::raw('IFNULL(SUM(opps.status = "won"),0) as won'))
            //     ->addSelect(DB::raw('IFNULL(SUM(opps.status = "lost"),0) as lost'))
            //     ->addSelect(DB::raw('IFNULL(SUM(opps.status = "cancelled"),0) as cancelled'))
            //     ->groupBy('user.id')
            //     ->orderBy('total', 'desc')
            //     ->get();

            $data =  User::whereHas(
                'roles',
                function ($q) {
                    $q->whereIn('name', ['presales_consultant']);
                }
            )
                ->leftJoin('opportunity_tasks as task', 'task.assigned_to', '=', 'users.id')
                ->leftJoin('opportunities as opps', 'task.opportunity_id', '=', 'opps.id')
                ->where(function ($query) use ($from, $to) {
                    $query->where('opps.submission_date', '>=', $from);
                    $query->where('opps.submission_date', '<=', $to);
                    // $query->orDoesntHave('opportunities');
                })
                ->select('users.name as user_name', 'users.id as user_id')
                ->addSelect(DB::raw('count(opps.id) as total'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.status = "won"),0) as won'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.status = "lost"),0) as lost'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.status = "cancelled"),0) as cancelled'))

                ->addSelect(DB::raw('IFNULL(SUM(opps.category = "proposal"),0) as proposal'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.category = "rfp writing"),0) as rfp_writing'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.category = "presentation"),0) as presentation'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.category = "demo"),0) as demo'))

                ->groupBy('users.id', 'users.name')
                ->orderBy('total', 'desc')
                ->get();

            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function countriesRequestByResources(DateRangeRequest $request)
    {
        try {

            $from = $request->from;
            $to = $request->to;

            // $data =  User::whereHas(
            //         'roles',
            //         function ($q) {
            //             $q->whereIn('name', ['presales_consultant', 'presales_manager']);
            //         }
            //     )
            //     ->leftJoin('opportunity_tasks as task', 'task.assigned_to', '=', 'users.id')
            //     ->leftJoin('opportunities as opps', 'task.opportunity_id', '=', 'opps.id')
            //     ->with('branch')
            //     ->where(function ($query) use ($from, $to) {
            //         $query->where('opps.submission_date', '>=', $from);
            //         $query->where('opps.submission_date', '<=', $to);
            //         // $query->orDoesntHave('opportunities');
            //     })
            //     ->select('users.name as user_name', 'users.id as user_id', 'opps.branch_id as branch_id')
            //     ->addSelect(DB::raw('count(opps.id) as total'))
            //     ->addSelect(DB::raw('IFNULL(SUM(opps.status = "won"),0) as won'))
            //     ->addSelect(DB::raw('IFNULL(SUM(opps.status = "lost"),0) as lost'))
            //     ->addSelect(DB::raw('IFNULL(SUM(opps.status = "cancelled"),0) as cancelled'))
            //     ->groupBy('users.id','opps.branch_id')
            //     // ->orderBy('total', 'desc')
            //     ->orderBy('user_name', 'asc')
            //     ->get();

            $data =  User::whereHas(
                'roles',
                function ($q) {
                    $q->whereIn('name', ['presales_consultant']);
                }
            )
                ->leftJoin('opportunity_tasks as task', 'task.assigned_to', '=', 'users.id')
                ->leftJoin('opportunities as opps', 'task.opportunity_id', '=', 'opps.id')
                ->rightJoin('branches as brnch', 'brnch.id', '=', 'opps.branch_id')
                ->where(function ($query) use ($from, $to) {
                    $query->where('opps.submission_date', '>=', $from);
                    $query->where('opps.submission_date', '<=', $to);
                    // $query->orDoesntHave('opportunities');
                })
                ->select('users.name as user_name', 'users.id as user_id', 'opps.branch_id as branch_id', 'brnch.name as branch_name')
                ->addSelect(DB::raw('count(opps.id) as total'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.status = "won"),0) as won'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.status = "lost"),0) as lost'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.status = "cancelled"),0) as cancelled'))
                // ->groupBy('users.id', 'opps.branch_id')
                ->groupBy('users.id', 'opps.branch_id', 'users.name', 'brnch.name')
                // ->orderBy('total', 'desc')
                ->orderBy('user_name', 'asc')
                ->get();


            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function profitByCountries(DateRangeRequest $request)
    {
        try {

            $from = $request->from;
            $to = $request->to;

            $data =  Opportunity::leftJoin('branches as branch', 'branch.id', '=', 'opportunities.branch_id')
                ->select('branch.name as country_name')
                ->addSelect(DB::raw('IFNULL(SUM(awarded_amount),0) as total_profit'))
                ->addSelect(DB::raw('IFNULL(SUM(proposed_value),0) as total_proposed'))
                ->where('submission_date', '>=', $from)
                ->where('status', '=', 'won')
                ->where('submission_date', '<=', $to)
                ->groupBy('country_name')
                ->orderBy('total_profit', 'desc')
                ->get();


            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function profitBySolution(DateRangeRequest $request)
    {
        try {

            $from = $request->from;
            $to = $request->to;

            $data =  Opportunity::select('solution')
                ->addSelect(DB::raw('IFNULL(SUM(awarded_amount),0) as total_profit'))
                ->where('submission_date', '>=', $from)
                ->where('submission_date', '<=', $to)
                ->where('status', '=', 'won')
                ->groupBy('solution')
                ->orderBy('total_profit', 'desc')
                ->get();


            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function totalProfit(DateRangeRequest $request)
    {
        try {

            $from = $request->from;
            $to = $request->to;

            $data =  Opportunity::
                addSelect(DB::raw('IFNULL(SUM(awarded_amount),0) as total_profit'))
                ->where('submission_date', '>=', $from)
                ->where('status', '=', 'won')
                ->where('submission_date', '<=', $to)
                ->get();


            return $data[0];
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function requestsPerMonthByCountry(DateRangeRequest $request)
    {
        try {

            $from = $request->from;
            $to = $request->to;
            $branch_id = $request->branch_id;

            $data =  Opportunity::where('submission_date', '>=', $from)
                ->where('submission_date', '<=', $to)
                ->where('branch_id', '<=', $branch_id)
                // ->select('branch_id')
                ->addSelect(DB::raw('count(*) as total'))
                ->addSelect(DB::raw('IFNULL(SUM(status = "won"),0) as won'))
                ->addSelect(DB::raw('IFNULL(SUM(status = "lost"),0) as lost'))
                ->addSelect(DB::raw('IFNULL(SUM(status = "cancelled"),0) as cancelled'))
                ->addSelect(DB::raw('IFNULL(SUM(category = "proposal"),0) as proposal'))
                ->addSelect(DB::raw('IFNULL(SUM(category = "rfp writing"),0) as rfp_writing'))
                ->addSelect(DB::raw('IFNULL(SUM(category = "presentation"),0) as presentation'))
                ->addSelect(DB::raw('IFNULL(SUM(category = "demo"),0) as demo'))
                ->selectRaw('year(submission_date) year, monthname(submission_date) month, month(submission_date) monthNum,  count(*) data')
                // ->addSelect(DB::raw("FORMAT(submission_date,'%M %Y') monthNum"))

                // ->addSelect(DB::raw("DATE_FORMAT(submission_date, '%m-%Y') new_date"))
                // ->addSelect(DB::raw('YEAR(submission_date) year, MONTH(submission_date) month'))
                // ->addSelect('IFNULL(SUM(opportunities.rfp_status = "inprogress"),0) as inprogress')
                ->groupBy('year', 'month', 'monthNum')
                ->orderBy('monthNum', 'asc')
                ->get();

            return $data;
            // return response([
            //     'data' => $data,
            //     'message' => "",
            //     'errors' => []
            // ], 200);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function requestsBySolution(DateRangeRequest $request)
    {
        try {

            $from = $request->from;
            $to = $request->to;

            $data =  Opportunity::select('solution')
                ->where('submission_date', '>=', $from)
                ->where('submission_date', '<=', $to)
                ->addSelect(DB::raw('count(*) as total'))
                ->addSelect(DB::raw('IFNULL(SUM(status = "won"),0) as won'))
                ->addSelect(DB::raw('IFNULL(SUM(status = "lost"),0) as lost'))
                ->addSelect(DB::raw('IFNULL(SUM(status = "cancelled"),0) as cancelled'))
                ->addSelect(DB::raw('IFNULL(SUM(category = "proposal"),0) as proposal'))
                ->addSelect(DB::raw('IFNULL(SUM(category = "rfp writing"),0) as rfp_writing'))
                ->addSelect(DB::raw('IFNULL(SUM(category = "presentation"),0) as presentation'))
                ->addSelect(DB::raw('IFNULL(SUM(category = "demo"),0) as demo'))
                ->groupBy('solution')
                ->orderBy('solution', 'desc')
                ->get();


            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function requestsPerMonth(DateRangeRequest $request)
    {
        try {

            $from = $request->from;
            $to = $request->to;

            $data =  Opportunity::where('submission_date', '>=', $from)
                ->where('submission_date', '<=', $to)
                // ->select('branch_id')
                ->addSelect(DB::raw('count(*) as total'))
                ->addSelect(DB::raw('IFNULL(SUM(status = "won"),0) as won'))
                ->addSelect(DB::raw('IFNULL(SUM(status = "lost"),0) as lost'))
                ->addSelect(DB::raw('IFNULL(SUM(status = "cancelled"),0) as cancelled'))
                ->addSelect(DB::raw('IFNULL(SUM(category = "proposal"),0) as proposal'))
                ->addSelect(DB::raw('IFNULL(SUM(category = "rfp writing"),0) as rfp_writing'))
                ->addSelect(DB::raw('IFNULL(SUM(category = "presentation"),0) as presentation'))
                ->addSelect(DB::raw('IFNULL(SUM(category = "demo"),0) as demo'))
                ->selectRaw('year(submission_date) year, monthname(submission_date) month, month(submission_date) monthNum,  count(*) data')
                // ->addSelect(DB::raw("FORMAT(submission_date,'%M %Y') monthNum"))

                // ->addSelect(DB::raw("DATE_FORMAT(submission_date, '%m-%Y') new_date"))
                // ->addSelect(DB::raw('YEAR(submission_date) year, MONTH(submission_date) month'))
                // ->addSelect('IFNULL(SUM(opportunities.rfp_status = "inprogress"),0) as inprogress')
                ->groupBy('year', 'month', 'monthNum')
                ->orderBy('monthNum', 'asc')
                ->get();

            return $data;
            // return response([
            //     'data' => $data,
            //     'message' => "",
            //     'errors' => []
            // ], 200);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function requestsPerMonthByResources(DateRangeRequest $request)
    {
        try {

            $from = $request->from;
            $to = $request->to;
            $user_id = $request->user_id;
            if(is_null($user_id) || empty($user_id)){
                return response()->json([
                    'message' => 'User is required.'], 400);
            }
            $data =  User::whereHas(
                'roles',
                function ($q) {
                    $q->whereIn('name', ['presales_consultant']);
                }
            )
                ->where('users.id', $user_id)
                ->leftJoin('opportunity_tasks as task', 'task.assigned_to', '=', 'users.id')
                ->leftJoin('opportunities as opps', 'task.opportunity_id', '=', 'opps.id')
                ->where(function ($query) use ($from, $to) {
                    $query->where('opps.submission_date', '>=', $from);
                    $query->where('opps.submission_date', '<=', $to);
                })
                ->addSelect(DB::raw('count(*) as total'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.status = "won"),0) as won'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.status = "lost"),0) as lost'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.status = "cancelled"),0) as cancelled'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.category = "proposal"),0) as proposal'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.category = "rfp writing"),0) as rfp_writing'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.category = "presentation"),0) as presentation'))
                ->addSelect(DB::raw('IFNULL(SUM(opps.category = "demo"),0) as demo'))
                ->selectRaw('year(opps.submission_date) year, monthname(opps.submission_date) month')
                ->groupBy('year', 'month')
                ->orderBy('month', 'asc')
                ->get();


            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
