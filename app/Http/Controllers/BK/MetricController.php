<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Billable_hours;
use App\User;
use App\Job;
use App\Announce;

use DB;
use Auth;
use Config;
use Carbon\Carbon;



class MetricController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
    	$dateRange = Carbon::now();
        

        //Get past Last Month = 4 Week Lag
        $endIniLag     = $dateRange->copy()->subWeek(4);
        $dateNow = Carbon::now();

        //Get past Last Month = 5 Week Lag
        $endPastLag     = $dateRange->copy()->subWeek(5);
        $iniPastLag     = $dateRange->copy()->subWeek(1);

    	 //Get past Last week = Week 1
        $endIniWeek1 = $dateNow;
        $IniWeek1 = $dateRange->copy()->subWeek(4);

        //Get past  2 week = Week 2
        $endIniWeek2 = $dateRange->copy()->subWeek(1);
        $IniWeek2 = $dateRange->copy()->subWeek(5);

        //Get past  3 week = Week 3
        $endIniWeek3 = $dateRange->copy()->subWeek(2);
        $IniWeek3 = $dateRange->copy()->subWeek(6);

        //Get past  4 week = Week 4
        $endIniWeek4 = $dateRange->copy()->subWeek(3);
        $IniWeek4 = $dateRange->copy()->subWeek(7);

        $user_id=Auth::user()->id;

        
        //Count variables
        $countEmployee=0;
        $countPortfolio=0;
        $countEvaluation=0;

        $job_portfolio=array();
        $job_site=array();
        $manager_id=0;
        $manager_list=array();
        
        
        //Square Feet
        $sumSquareFeet=0;        


        $job_list_str="";
        $job_list_array=array();
        $comment_list=array();

    						
        //Get user role
        $role_user=Auth::user()->getRole();

        $total_expense_lag=0;
        $total_bill_lag=0;
        $total_labor_tax_lag=0;
        $total_budget_monthly_lag=0;

        $total_expense_lag_past=0;
        $total_bill_lag_past=0;
        $total_labor_tax_lag_past=0;
        $total_budget_monthly_lag_past=0;

        $total_expense_week1=1;
        $total_labor_tax_week1=0;
        $total_budget_monthly_week1=0;

        $total_expense_week2=0;
        $total_labor_tax_week2=0;
        $total_budget_monthly_week2=0;

        $total_expense_week3=0;
        $total_labor_tax_week3=0;
        $total_budget_monthly_week3=0;

        $total_expense_week4=0;
        $total_labor_tax_week4=0;
        $total_budget_monthly_week4=0;

        $total_budget_monthly=0;

        $total_evaluation=0;
        $total_evaluation_no=0;
        $total_evaluation_user=0;

        $total_evaluation_past=0;
        $total_evaluation_no_past=0;

        $total_evaluation_param=array(
            'param1'    =>  0,
            'param2'    =>  0,
            'param3'    =>  0,
            'param4'    =>  0,
            'param5'    =>  0,
        );

        $total_supplies=array(
            'expense'           => 0,
            'budget_monthly'    => 0,
        );

        $total_supplies_past=array(
            'expense'           => 0,
            'budget_monthly'    => 0,
        );

        $total_salies_wages_amount=array(
            'expense'      => 0,
            'labor_tax'    => 0,
        );

        $total_salies_wages_amount_past=array(
            'expense'      => 0,
            'labor_tax'    => 0,
        );

        $total_hours=array(
            'used'       => 0,
            'budget'     => 0,
        );

        $total_hours_past=array(
            'used'       => 0,
            'budget'     => 0,
        );

        
        if($role_user==Config::get('roles.AREA_MANAGER')){
    
            $manager_id=Auth::user()->getManagerId();

            //List of porfolio
            $job_portfolio  = DB::table('job')
                            ->where('manager', $manager_id)
                            ->where('is_parent', 0)
                            ->get();


            //List Site                
            $job_site       = DB::table('job')
                            ->where('manager', $manager_id)
                            ->where('is_parent', 1)
                            ->get();

            //Count Employee                
            $countEmployee  = User::CountManagerEmployee($manager_id);

            //Count Portfolio
            $countPortfolio  = Job::CountPortfolio($manager_id);


             //4 Week Lag - Ini
                 $jobList = DB::table('expense')
                                    ->select(DB::raw('DISTINCT(expense.job_number) as "job_number"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $endIniLag->format('Y-m-d'))
                                    ->where('posting_date', '<=', $dateNow->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();                

                foreach ($jobList as $value) {
                    $job_list_array[]=$value->job_number;         
                }           

                $job_list_str=implode(",", $job_list_array);         


                $sumSquareFeetQuery = DB::table('job')
                                    ->select(DB::raw('SUM(square_feet) as "total_feet"'))
                                    ->whereIn('job_number', $job_list_array)
                                    ->get();

                foreach ($sumSquareFeetQuery as $value) {
                    $sumSquareFeet=$value->total_feet;         
                }


                $query_expense_lag  = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $endIniLag->format('Y-m-d'))
                                    ->where('posting_date', '<=', $dateNow->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_expense_lag as $value) {
                    $total_expense_lag=$value->total_amount;         
                }

                $query_bill_lag  = DB::table('billable_hours')
                                    ->select(DB::raw('SUM((regular_hours+overtime_hours)*pay_rate*1.19) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '>', $endIniWeek3->format('Y-m-d'))
                                    ->where('work_date', '<=', $dateNow->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->get();

                foreach ($query_bill_lag as $value) {
                    $total_bill_lag=$value->total_amount;         
                }

                $query_labor_tax_lag  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('date', '>', $endIniLag->format('Y-m-d'))
                                    ->where('date', '<=', $dateNow->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_lag as $value) {
                    $total_labor_tax_lag=$value->total_amount;         
                }

                $query_budget_monthly_lag  = DB::table('budget_monthly')
                                    ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                    ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                    ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_budget_monthly_lag as $value) {
                    $total_budget_monthly_lag=$value->total_amount;         
                }

            //4 Week Lag - End

            //4 Week Lag Past - Ini

                $query_expense_lag_past= DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $endPastLag->format('Y-m-d'))
                                    ->where('posting_date', '<=', $iniPastLag->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_expense_lag_past as $value) {
                    $total_expense_lag_past=$value->total_amount;         
                }

                $query_bill_lag_past = DB::table('billable_hours')
                                    ->select(DB::raw('SUM((regular_hours+overtime_hours)*pay_rate*1.19) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '>', $endIniWeek3->format('Y-m-d'))
                                    ->where('work_date', '<=', $iniPastLag->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->get();

                foreach ($query_bill_lag_past as $value) {
                    $total_bill_lag_past=$value->total_amount;         
                }


                $query_labor_tax_lag_past  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('date', '>', $endPastLag->format('Y-m-d'))
                                    ->where('date', '<=', $iniPastLag->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_lag_past as $value) {
                    $total_labor_tax_lag_past=$value->total_amount;         
                    if($total_labor_tax_lag_past==0 || $total_labor_tax_lag_past==null)
                    $total_labor_tax_lag_past=0;      
                }

                $query_budget_monthly_lag_past  = DB::table('budget_monthly')
                                    ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                    ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                    ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_budget_monthly_lag_past as $value) {
                    $total_budget_monthly_lag_past=$value->total_amount;   
                    if($total_budget_monthly_lag_past==0 || $total_budget_monthly_lag_past==null)
                        $total_budget_monthly_lag_past=0;      
                }

            //4 Week Past Lag - End    


            // Monthly budget calculation    
            $query_budget_monthly  = DB::table('budget_monthly')
                                ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                ->where('manager', $manager_id)
                                ->where('financial', 1)
                                ->get();

            foreach ($query_budget_monthly as $value) {
                $total_budget_monthly=$value->total_amount;         
            }


            //Last Week = Week 1 - Ini
                $total_expense_week1=$total_expense_lag;        
                if($total_expense_week1==0 || $total_expense_week1==null)
                    $total_expense_week1=0;
                
                $total_labor_tax_week1=$total_labor_tax_lag;         
                if($total_labor_tax_week1==0 || $total_labor_tax_week1==null)
                    $total_labor_tax_week1=0;
                

            //Last Week = Week 1 - End

            //Week 2 - Ini

                $total_expense_week2=$total_expense_lag_past; 
                if($total_expense_week2==0 || $total_expense_week2==null)
                    $total_expense_week2=0;        
                
                $total_labor_tax_week2=$total_labor_tax_lag_past;  
                if($total_labor_tax_week2==0 || $total_labor_tax_week2==null)
                    $total_labor_tax_week2=0;       
                
            //Week 2 - End

             //Week 3 - Ini 
                $query_expense_week3    = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $IniWeek3->format('Y-m-d'))
                                    ->where('posting_date', '<=', $endIniWeek3->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_expense_week3 as $value) {
                    $total_expense_week3=$value->total_amount;  
                    if($total_expense_week3==0 || $total_expense_week3==null)
                        $total_expense_week3=0;       
                } 

                $query_labor_tax_week3  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '>', $IniWeek3->format('Y-m-d'))
                                    ->where('labor_tax.date', '<=', $endIniWeek3->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_week3 as $value) {
                    $total_labor_tax_week3=$value->total_amount;         
                    if($total_labor_tax_week3==0 || $total_labor_tax_week3==null)
                        $total_labor_tax_week3=0; 
                }

            //Week 3 - End

            //Week 4 - Ini 
                $query_expense_week4    = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $IniWeek4->format('Y-m-d'))
                                    ->where('posting_date', '<=', $endIniWeek4->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_expense_week4 as $value) {
                    $total_expense_week4=$value->total_amount;   
                    if($total_expense_week4==0 || $total_expense_week4==null)
                        $total_expense_week4=0;         
                } 

                 $query_labor_tax_week4  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '>', $IniWeek4->format('Y-m-d'))
                                    ->where('labor_tax.date', '<=', $endIniWeek4->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_week4 as $value) {
                    $total_labor_tax_week4=$value->total_amount;   
                    if($total_labor_tax_week4==0 || $total_labor_tax_week4==null)
                        $total_labor_tax_week4=0;       
                }

            //Week 4 - End


            //Last Week = Supplies(Account Number=4090) Week 1 & 2 - Ini
                // Monthly budget calculation Supplies   
               $query_budget_monthly  = DB::table('budget_monthly')
                                    ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                    ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                    ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('budget_monthly.account_number', 4090)
                                    ->get();

                foreach ($query_budget_monthly as $value) {
                    $total_supplies['budget_monthly']=$value->total_amount;         
                }

                $query_expense_week1   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('posting_date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('expense.account_number', 4090)
                                    ->get();

                foreach ($query_expense_week1 as $value) {
                    $total_supplies['expense']=$value->total_amount;        
                    if($total_supplies['expense']==0 || $total_supplies['expense']==null)
                        $total_supplies['expense']=0;
                }


                $query_expense_week2   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('posting_date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('expense.account_number', 4090)
                                    ->get();

                foreach ($query_expense_week2 as $value) {
                    $total_supplies_past['expense']=$value->total_amount;        
                    if($total_supplies_past['expense']==0 || $total_supplies_past['expense']==null)
                        $total_supplies_past['expense']=0;
                }


            //Last Week = Week 1 & 2 Supplies  - End    


             //Last Week = Labor & Tax(Account Number=4000, 4275, 4278, 4277, 4280, 4276 ) Week 1 & 2 - Ini
                // Monthly budget calculation Supplies   
                $query_labor_tax_week1  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('labor_tax.date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_week1 as $value) {
                    $total_salies_wages_amount['labor_tax']=$value->total_amount;         
                    if($total_salies_wages_amount['labor_tax']==0 || $total_salies_wages_amount['labor_tax']==null)
                        $total_salies_wages_amount['labor_tax']=0;
                }

                $query_expense_week1   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->whereIn('expense.account_number', [4000, 4001, 4003, 4004, 4005, 4007, 4010, 4011, 4020, 4275, 4276, 4277, 4278, 4279, 4280])
                                    ->get();

                foreach ($query_expense_week1 as $value) {
                    $total_salies_wages_amount['expense']=$value->total_amount;        
                    if($total_salies_wages_amount['expense']==0 || $total_salies_wages_amount['expense']==null)
                        $total_salies_wages_amount['expense']=0;
                }

                 $query_labor_tax_week2  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('labor_tax.date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_week2 as $value) {
                    $total_salies_wages_amount_past['labor_tax']=$value->total_amount;         
                    if($total_salies_wages_amount_past['labor_tax']==0 || $total_salies_wages_amount_past['labor_tax']==null)
                        $total_salies_wages_amount_past['labor_tax']=0;
                }


                $query_expense_week2   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->whereIn('expense.account_number', [4000, 4001, 4003, 4004, 4005, 4007, 4010, 4011, 4020, 4275, 4276, 4277, 4278, 4279, 4280])
                                    ->get();

                foreach ($query_expense_week2 as $value) {
                    $total_salies_wages_amount_past['expense']=$value->total_amount;        
                    if($total_salies_wages_amount_past['expense']==0 || $total_salies_wages_amount_past['expense']==null)
                        $total_salies_wages_amount_past['expense']=0;
                }


            //Last Week = Week 1 & 2 Supplies  - End     


            //Last Week =  Budget - Billable Hours - Ini
               $query_hours  = DB::table('billable_hours')
                                    ->select(DB::raw('SUM(regular_hours) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('work_date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours['used']=$value->total_amount;         
                }

                $query_hours  = DB::table('budget')
                                    ->select(DB::raw('SUM(hours) as "total_amount"'))
                                    ->join('job', 'budget.job_number', '=', 'job.job_number')
                                    ->where('date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours['budget']=$value->total_amount;         
                }

            //Last Week =  Budget - Billable Hours - End 

                //Past Week =  Budget - Billable Hours - Ini
               $query_hours  = DB::table('billable_hours')
                                    ->select(DB::raw('SUM(regular_hours) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('work_date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours_past['used']=$value->total_amount;         
                }

                $query_hours  = DB::table('budget')
                                    ->select(DB::raw('SUM(hours) as "total_amount"'))
                                    ->join('job', 'budget.job_number', '=', 'job.job_number')
                                    ->where('date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours_past['budget']=$value->total_amount;         
                }

            //Past Week =  Budget - Billable Hours - End  
   

            
            /*Evaluation functions - INI*/
                $query_evaluation  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1)+SUM(parameter2)+SUM(parameter3)+SUM(parameter4)+SUM(parameter5) as "total_amount"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation as $value) {
                    $total_evaluation=$value->total_amount;         
                }

                $query_evaluation  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1) as "param1", SUM(parameter2) as "param2", SUM(parameter3) as "param3", SUM(parameter4) as "param4",SUM(parameter5) as "param5"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation as $value) {
                    $total_evaluation_param['param1']=$value->param1;
                    $total_evaluation_param['param2']=$value->param2;
                    $total_evaluation_param['param3']=$value->param3;         
                    $total_evaluation_param['param4']=$value->param4;
                    $total_evaluation_param['param5']=$value->param5;

                }

                $query_evaluation_past  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1)+SUM(parameter2)+SUM(parameter3)+SUM(parameter4)+SUM(parameter5) as "total_amount"'))
                                        ->where('created_at', '>', $endPastLag)
                                        ->where('created_at', '<=', $iniPastLag)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation_past as $value) {
                    $total_evaluation_past=$value->total_amount;         
                }


                //Number of evaluations
                $total_evaluation_no  = DB::table('evaluation')
                                        ->select(DB::raw('id'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->count();

                //Number of evaluations Past
                $total_evaluation_no_past = DB::table('evaluation')
                                        ->select(DB::raw('id'))
                                        ->where('created_at', '>', $endPastLag)
                                        ->where('created_at', '<=', $iniPastLag)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->count();

                //Number of users evaluated                        
                $query_evaluation_user  = DB::table('evaluation')
                                        ->select(DB::raw('COUNT(DISTINCT(evaluate_user_id)) as "total"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation_user as $value) {
                    $total_evaluation_user=$value->total;         
                }


            /*Evaluation Functions - END*/

        }elseif($role_user==Config::get('roles.DIR_POS')){
            $manager_list   = DB::table('users')
                            -> where('manager_id', '!=', 0)
                            -> where('active', 1)
                            -> orderBy('manager_id', 'asc')
                            -> get();

            $manager_list_array = array();

            foreach ($manager_list as $manager) {
                $manager_list_array[]=$manager->manager_id;
            }

            //Add Corporate and None Managers - 90 Corporate - 91 None
            $manager_list_array[]=90;
            $manager_list_array[]=91;


            //List of porfolio
            $job_portfolio  = DB::table('job')
                            ->whereIn('manager', $manager_list_array)
                            ->where('is_parent', 0)
                            ->get();


            //List Site                
            $job_site       = DB::table('job')
                            ->whereIn('manager', $manager_list_array)
                            ->where('is_parent', 1)
                            ->get();

            //Count Managers Employee               
            $countEmployee  = User::CountManagerListEmployee($manager_list_array);

            //Count Same role
            $countEmployeeRole  = User::CountRoleListEmployee(Config::get('roles.DIR_POS'), Auth::user()->id);

            //Count Managers
            $countManager  = User::CountManagerList();

            $countEvaluation= $countEmployeeRole+ $countManager;


            //Count Portfolio
            $countPortfolio  = Job::CountPortfolioList($manager_list_array);


             //4 Week Lag - Ini
                 $jobList = DB::table('expense')
                                    ->select(DB::raw('DISTINCT(expense.job_number) as "job_number"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $endIniLag->format('Y-m-d'))
                                    ->where('posting_date', '<=', $dateNow->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->get();                

                foreach ($jobList as $value) {
                    $job_list_array[]=$value->job_number;         
                }           

                $job_list_str=implode(",", $job_list_array);         


                $sumSquareFeetQuery = DB::table('job')
                                    ->select(DB::raw('SUM(square_feet) as "total_feet"'))
                                    ->whereIn('job_number', $job_list_array)
                                    ->get();

                foreach ($sumSquareFeetQuery as $value) {
                    $sumSquareFeet=$value->total_feet;         
                }


                $query_expense_lag  = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $endIniLag->format('Y-m-d'))
                                    ->where('posting_date', '<=', $dateNow->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_expense_lag as $value) {
                    $total_expense_lag=$value->total_amount;         
                }

                $query_bill_lag  = DB::table('billable_hours')
                                    ->select(DB::raw('SUM((regular_hours+overtime_hours)*pay_rate*1.19) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '>', $endIniWeek3->format('Y-m-d'))
                                    ->where('work_date', '<=', $dateNow->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->get();

                foreach ($query_bill_lag as $value) {
                    $total_bill_lag=$value->total_amount;         
                }


                $query_labor_tax_lag  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('date', '>', $endIniLag->format('Y-m-d'))
                                    ->where('date', '<=', $dateNow->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_lag as $value) {
                    $total_labor_tax_lag=$value->total_amount;         
                }

                $query_budget_monthly_lag  = DB::table('budget_monthly')
                                    ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                    ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                    ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_budget_monthly_lag as $value) {
                    $total_budget_monthly_lag=$value->total_amount;         
                }

            //4 Week Lag - End

            //4 Week Lag Past - Ini

                $query_expense_lag_past= DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $endPastLag->format('Y-m-d'))
                                    ->where('posting_date', '<=', $iniPastLag->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_expense_lag_past as $value) {
                    $total_expense_lag_past=$value->total_amount;         
                }

                $query_bill_lag_past = DB::table('billable_hours')
                                    ->select(DB::raw('SUM((regular_hours+overtime_hours)*pay_rate*1.19) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '>', $endIniWeek3->format('Y-m-d'))
                                    ->where('work_date', '<=', $iniPastLag->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->get();

                foreach ($query_bill_lag_past as $value) {
                    $total_bill_lag_past=$value->total_amount;         
                }


                $query_labor_tax_lag_past  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('date', '>', $endPastLag->format('Y-m-d'))
                                    ->where('date', '<=', $iniPastLag->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_lag_past as $value) {
                    $total_labor_tax_lag_past=$value->total_amount;         
                    if($total_labor_tax_lag_past==0 || $total_labor_tax_lag_past==null)
                    $total_labor_tax_lag_past=0;      
                }

                $query_budget_monthly_lag_past  = DB::table('budget_monthly')
                                    ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                    ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                    ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_budget_monthly_lag_past as $value) {
                    $total_budget_monthly_lag_past=$value->total_amount;   
                    if($total_budget_monthly_lag_past==0 || $total_budget_monthly_lag_past==null)
                        $total_budget_monthly_lag_past=0;      
                }

            //4 Week Past Lag - End    


            // Monthly budget calculation    
            $query_budget_monthly  = DB::table('budget_monthly')
                                ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                ->whereIn('manager', $manager_list_array)
                                ->where('financial', 1)
                                ->get();

            foreach ($query_budget_monthly as $value) {
                $total_budget_monthly=$value->total_amount;         
            }

               
            //Last Week = Week 1 - Ini
                
                $total_expense_week1=$total_expense_lag;        
                if($total_expense_week1==0 || $total_expense_week1==null)
                    $total_expense_week1=0;
                
                    $total_labor_tax_week1=$total_labor_tax_lag;         
                    if($total_labor_tax_week1==0 || $total_labor_tax_week1==null)
                        $total_labor_tax_week1=0;
                
            //Last Week = Week 1 - End

            //Week 2 - Ini 
                
                
                    $total_expense_week2=$total_expense_lag_past; 
                    if($total_expense_week2==0 || $total_expense_week2==null)
                        $total_expense_week2=0;        
        
                    $total_labor_tax_week2=$total_labor_tax_lag_past;  
                    if($total_labor_tax_week2==0 || $total_labor_tax_week2==null)
                        $total_labor_tax_week2=0;       
                

            //Week 2 - End

             //Week 3 - Ini 
                $query_expense_week3    = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek3->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek3->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_expense_week3 as $value) {
                    $total_expense_week3=$value->total_amount;  
                    if($total_expense_week3==0 || $total_expense_week3==null)
                        $total_expense_week3=0;       
                } 

                $query_labor_tax_week3  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '<=', $endIniWeek3->format('Y-m-d'))
                                    ->where('labor_tax.date', '>', $IniWeek3->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_week3 as $value) {
                    $total_labor_tax_week3=$value->total_amount;         
                    if($total_labor_tax_week3==0 || $total_labor_tax_week3==null)
                        $total_labor_tax_week3=0; 
                }

            //Week 3 - End

            //Week 4 - Ini 
                $query_expense_week4    = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek4->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek4->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_expense_week4 as $value) {
                    $total_expense_week4=$value->total_amount;   
                    if($total_expense_week4==0 || $total_expense_week4==null)
                        $total_expense_week4=0;         
                } 

                 $query_labor_tax_week4  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '<=', $endIniWeek4->format('Y-m-d'))
                                    ->where('labor_tax.date', '>', $IniWeek4->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_week4 as $value) {
                    $total_labor_tax_week4=$value->total_amount;   
                    if($total_labor_tax_week4==0 || $total_labor_tax_week4==null)
                        $total_labor_tax_week4=0;       
                }

            //Week 4 - End


            //Last Week = Supplies(Account Number=4090) Week 1 & 2 - Ini
                // Monthly budget calculation Supplies   
               $query_budget_monthly  = DB::table('budget_monthly')
                                    ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                    ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                    ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->where('budget_monthly.account_number', 4090)
                                    ->get();

                foreach ($query_budget_monthly as $value) {
                    $total_supplies['budget_monthly']=$value->total_amount;         
                }

                $query_expense_week1   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek1->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->where('expense.account_number', 4090)
                                    ->get();

                foreach ($query_expense_week1 as $value) {
                    $total_supplies['expense']=$value->total_amount;        
                    if($total_supplies['expense']==0 || $total_supplies['expense']==null)
                        $total_supplies['expense']=0;
                }


                $query_expense_week2   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek2->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->where('expense.account_number', 4090)
                                    ->get();

                foreach ($query_expense_week2 as $value) {
                    $total_supplies_past['expense']=$value->total_amount;        
                    if($total_supplies_past['expense']==0 || $total_supplies_past['expense']==null)
                        $total_supplies_past['expense']=0;
                }


            //Last Week = Week 1 & 2 Supplies  - End    


             //Last Week = Labor & Tax(Account Number=4000, 4275, 4278, 4277, 4280, 4276 ) Week 1 & 2 - Ini
                // Monthly budget calculation Supplies   
                $query_labor_tax_week1  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('labor_tax.date', '>', $IniWeek1->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_week1 as $value) {
                    $total_salies_wages_amount['labor_tax']=$value->total_amount;         
                    if($total_salies_wages_amount['labor_tax']==0 || $total_salies_wages_amount['labor_tax']==null)
                        $total_salies_wages_amount['labor_tax']=0;
                }

                $query_expense_week1   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek1->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->whereIn('expense.account_number', [4000, 4001, 4003, 4004, 4005, 4007, 4010, 4011, 4020, 4275, 4276, 4277, 4278, 4279, 4280])
                                    ->get();

                foreach ($query_expense_week1 as $value) {
                    $total_salies_wages_amount['expense']=$value->total_amount;        
                    if($total_salies_wages_amount['expense']==0 || $total_salies_wages_amount['expense']==null)
                        $total_salies_wages_amount['expense']=0;
                }

                 $query_labor_tax_week2  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('labor_tax.date', '>', $IniWeek2->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_week2 as $value) {
                    $total_salies_wages_amount_past['labor_tax']=$value->total_amount;         
                    if($total_salies_wages_amount_past['labor_tax']==0 || $total_salies_wages_amount_past['labor_tax']==null)
                        $total_salies_wages_amount_past['labor_tax']=0;
                }


                $query_expense_week2   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek2->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->where('financial', 1)
                                    ->whereIn('expense.account_number', [4000, 4001, 4003, 4004, 4005, 4007, 4010, 4011, 4020, 4275, 4276, 4277, 4278, 4279, 4280])
                                    ->get();

                foreach ($query_expense_week2 as $value) {
                    $total_salies_wages_amount_past['expense']=$value->total_amount;        
                    if($total_salies_wages_amount_past['expense']==0 || $total_salies_wages_amount_past['expense']==null)
                        $total_salies_wages_amount_past['expense']=0;
                }


            //Last Week = Week 1 & 2 Supplies  - End     


            //Last Week =  Budget - Billable Hours - Ini
               $query_hours  = DB::table('billable_hours')
                                    ->select(DB::raw('SUM(regular_hours) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('work_date', '>', $IniWeek1->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours['used']=$value->total_amount;         
                }

                $query_hours  = DB::table('budget')
                                    ->select(DB::raw('SUM(hours) as "total_amount"'))
                                    ->join('job', 'budget.job_number', '=', 'job.job_number')
                                    ->where('date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('date', '>', $IniWeek1->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours['budget']=$value->total_amount;         
                }

            //Last Week =  Budget - Billable Hours - End 

                //Past Week =  Budget - Billable Hours - Ini
               $query_hours  = DB::table('billable_hours')
                                    ->select(DB::raw('SUM(regular_hours) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('work_date', '>', $IniWeek2->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours_past['used']=$value->total_amount;         
                }

                $query_hours  = DB::table('budget')
                                    ->select(DB::raw('SUM(hours) as "total_amount"'))
                                    ->join('job', 'budget.job_number', '=', 'job.job_number')
                                    ->where('date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('date', '>', $IniWeek2->format('Y-m-d'))
                                    ->whereIn('manager', $manager_list_array)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours_past['budget']=$value->total_amount;         
                }

            //Past Week =  Budget - Billable Hours - End  

            /*Evaluation functions - INI*/
                $query_evaluation  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1)+SUM(parameter2)+SUM(parameter3)+SUM(parameter4)+SUM(parameter5) as "total_amount"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation as $value) {
                    $total_evaluation=$value->total_amount;         
                }

                $query_evaluation  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1) as "param1", SUM(parameter2) as "param2", SUM(parameter3) as "param3", SUM(parameter4) as "param4",SUM(parameter5) as "param5"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation as $value) {
                    $total_evaluation_param['param1']=$value->param1;
                    $total_evaluation_param['param2']=$value->param2;
                    $total_evaluation_param['param3']=$value->param3;         
                    $total_evaluation_param['param4']=$value->param4;
                    $total_evaluation_param['param5']=$value->param5;

                }

                $query_evaluation_past  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1)+SUM(parameter2)+SUM(parameter3)+SUM(parameter4)+SUM(parameter5) as "total_amount"'))
                                        ->where('created_at', '>', $endPastLag)
                                        ->where('created_at', '<=', $iniPastLag)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation_past as $value) {
                    $total_evaluation_past=$value->total_amount;         
                }


                //Number of evaluations
                $total_evaluation_no  = DB::table('evaluation')
                                        ->select(DB::raw('id'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->count();

                //Number of evaluations Past
                $total_evaluation_no_past = DB::table('evaluation')
                                        ->select(DB::raw('id'))
                                        ->where('created_at', '>', $endPastLag)
                                        ->where('created_at', '<=', $iniPastLag)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->count();

                //Number of users evaluated                        
                $query_evaluation_user  = DB::table('evaluation')
                                        ->select(DB::raw('COUNT(DISTINCT(evaluate_user_id)) as "total"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation_user as $value) {
                    $total_evaluation_user=$value->total;         
                }


            /*Evaluation Functions - END*/    


        }elseif($role_user==Config::get('roles.SUPERVISOR') || $role_user==Config::get('roles.AREA_SUPERVISOR')){

            $primary_job=Auth::user()->gePrimayJob();

            $job_query     = DB::table('job')
                            ->where('job_number', $primary_job)
                            ->get();

            foreach ($job_query as $value) {
                $manager_id=$value->manager;         
            } 


            //Count Employee                
            $countEmployee  = User::CountManagerEmployee($manager_id);
            $countEmployee--;

            /*Evaluation functions - INI*/
                $query_evaluation  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1)+SUM(parameter2)+SUM(parameter3)+SUM(parameter4)+SUM(parameter5) as "total_amount"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation as $value) {
                    $total_evaluation=$value->total_amount;         
                }

                $query_evaluation  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1) as "param1", SUM(parameter2) as "param2", SUM(parameter3) as "param3", SUM(parameter4) as "param4",SUM(parameter5) as "param5"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation as $value) {
                    $total_evaluation_param['param1']=$value->param1;
                    $total_evaluation_param['param2']=$value->param2;
                    $total_evaluation_param['param3']=$value->param3;         
                    $total_evaluation_param['param4']=$value->param4;
                    $total_evaluation_param['param5']=$value->param5;

                }

                $query_evaluation_past  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1)+SUM(parameter2)+SUM(parameter3)+SUM(parameter4)+SUM(parameter5) as "total_amount"'))
                                        ->where('created_at', '>', $endPastLag)
                                        ->where('created_at', '<=', $iniPastLag)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation_past as $value) {
                    $total_evaluation_past=$value->total_amount;         
                }


                //Number of evaluations
                $total_evaluation_no  = DB::table('evaluation')
                                        ->select(DB::raw('id'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->count();

                //Number of evaluations Past
                $total_evaluation_no_past = DB::table('evaluation')
                                        ->select(DB::raw('id'))
                                        ->where('created_at', '>', $endPastLag)
                                        ->where('created_at', '<=', $iniPastLag)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->count();

                //Number of users evaluated                        
                $query_evaluation_user  = DB::table('evaluation')
                                        ->select(DB::raw('COUNT(DISTINCT(evaluate_user_id)) as "total"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation_user as $value) {
                    $total_evaluation_user=$value->total;         
                }


            /*Evaluation Functions - END*/
        }elseif($role_user==Config::get('roles.EMPLOYEE')){
            /*Evaluation functions - INI*/
                $query_evaluation  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1)+SUM(parameter2)+SUM(parameter3)+SUM(parameter4)+SUM(parameter5) as "total_amount"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation as $value) {
                    $total_evaluation=$value->total_amount;         
                }

                $query_evaluation  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1) as "param1", SUM(parameter2) as "param2", SUM(parameter3) as "param3", SUM(parameter4) as "param4",SUM(parameter5) as "param5"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation as $value) {
                    $total_evaluation_param['param1']=$value->param1;
                    $total_evaluation_param['param2']=$value->param2;
                    $total_evaluation_param['param3']=$value->param3;         
                    $total_evaluation_param['param4']=$value->param4;
                    $total_evaluation_param['param5']=$value->param5;

                }

                $query_evaluation_past  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1)+SUM(parameter2)+SUM(parameter3)+SUM(parameter4)+SUM(parameter5) as "total_amount"'))
                                        ->where('created_at', '>', $endPastLag)
                                        ->where('created_at', '<=', $iniPastLag)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation_past as $value) {
                    $total_evaluation_past=$value->total_amount;         
                }


                //Number of evaluations
                $total_evaluation_no  = DB::table('evaluation')
                                        ->select(DB::raw('id'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->count();

                //Number of evaluations Past
                $total_evaluation_no_past = DB::table('evaluation')
                                        ->select(DB::raw('id'))
                                        ->where('created_at', '>', $endPastLag)
                                        ->where('created_at', '<=', $iniPastLag)
                                        ->where('evaluate_user_id', Auth::user()->id)
                                        ->count();

                //Number of users evaluated                        
                $query_evaluation_user  = DB::table('evaluation')
                                        ->select(DB::raw('COUNT(DISTINCT(evaluate_user_id)) as "total"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('user_id', Auth::user()->id)
                                        ->get();

                foreach ($query_evaluation_user as $value) {
                    $total_evaluation_user=$value->total;         
                }


            /*Evaluation Functions - END*/  

            $comment_list = DB::table('evaluation')
                        ->select(DB::raw('*')) 
                        ->where('description', '!=',  '')
                        ->where('evaluate_user_id',   Auth::user()->id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(100);  
        }

        //Normalize data

        if($sumSquareFeet==0)
            $sumSquareFeet=1;
    

        //New Calculations
        $calc_val=$total_budget_monthly_lag+$total_labor_tax_lag;
        if($calc_val==0)
            $calc_val=1;
        $lag_differencial=(($total_expense_lag+$total_bill_lag)*100)/($calc_val);

        $budget_cant=$total_budget_monthly_lag_past+$total_labor_tax_lag_past;
        if($budget_cant==0)
            $budget_cant=1;
        $lag_differencial_past=(($total_expense_lag_past+$total_bill_lag_past)*100)/($budget_cant);



        $expense_calc_week1=$total_expense_week1+$total_bill_lag;
        $expense_calc_week2=$total_expense_week2+$total_bill_lag_past;
        if($expense_calc_week2==0 || $expense_calc_week2==null)
            $expense_calc_week2=1;
        
        $cost_past_week=$lag_differencial_past-$lag_differencial;

        $expense_week1=$total_expense_week1+$total_bill_lag;
        $expense_week2=$total_expense_week2+$total_bill_lag_past;
        $expense_week3=$total_expense_week3;
        $expense_week4=$total_expense_week4;

        $budget_week1=$total_budget_monthly+$total_labor_tax_week1;
        $budget_week2=$total_budget_monthly+$total_labor_tax_week2;
        $budget_week3=$total_budget_monthly+$total_labor_tax_week3;
        $budget_week4=$total_budget_monthly+$total_labor_tax_week4;


        $budget_cant=$budget_week1;
        if($budget_cant==0)
            $budget_cant=1;
        $lag_past_week=($expense_week1*100)/($budget_cant);

        //$cost_past_week=$total_expense_week1;


        /*Announcement list - INI*/
        switch ($role_user) {
            case '4':
                $permission_filter='1____';
                break;
            case '6':
                $permission_filter='_1___';
                break;
            case '5':
                $permission_filter='__1__';
                break;
            case '8':
                $permission_filter='___1_';
                break;
            case '9':
                $permission_filter='____1';
                break;
            default:
                $permission_filter='2____';
                break;
        }

        $result_announce = DB::table('announce')
                    ->select(DB::raw('*'))
                    ->where('status', 1)
                    ->where('permission', 'like' , $permission_filter)
                    ->orderBy('closing_date', 'desc')
                    ->paginate(50);

        /*Announcement list - END*/

        return view('metrics.page', [
            'user_id'                           => $user_id, 
            'count_employee'                    => $countEmployee,
            'count_portfolio'                   => $countPortfolio, 
            'count_evaluation'                  => $countEvaluation,             
            'sum_square_feet'                   => $sumSquareFeet,
            'role_user'                         => $role_user,
            'manager_list'                      => $manager_list,  
            'job_portfolio'                     => $job_portfolio,
            'job_site'                          => $job_site, 
            'job_list_str'                      => $job_list_str,
            'job_portfolio_id'                  => 0,
            'manager_id'                        => $manager_id,
            'total_expense_lag'                 => $total_expense_lag,
            'total_labor_tax_lag'               => $total_labor_tax_lag,
            'total_budget_monthly_lag'          => $total_budget_monthly_lag,
            'total_bill_lag'                    => $total_bill_lag,
            'cost_past_week'                    => $cost_past_week,
            'lag_differencial'                  => $lag_differencial,
            'lag_differencial_past'             => $lag_differencial_past,
            'lag_past_week'                     => $lag_past_week,
            'expense_week1'                     => $expense_week1,
            'expense_week2'                     => $expense_week2,
            'expense_week3'                     => $expense_week3,
            'expense_week4'                     => $expense_week4,
            'budget_week1'                      => $budget_week1,
            'budget_week2'                      => $budget_week2,
            'budget_week3'                      => $budget_week3,
            'budget_week4'                      => $budget_week4,
            'total_evaluation'                  => $total_evaluation,
            'total_evaluation_no'               => $total_evaluation_no,
            'total_evaluation_past'             => $total_evaluation_past,
            'total_evaluation_no_past'          => $total_evaluation_no_past,
            'total_evaluation_user'             => $total_evaluation_user,
            'total_evaluation_param'            => $total_evaluation_param,
            'total_supplies'                    => $total_supplies,
            'total_supplies_past'               => $total_supplies_past,
            'total_salies_wages_amount'         => $total_salies_wages_amount,
            'total_salies_wages_amount_past'    => $total_salies_wages_amount_past,
            'total_hours'                       => $total_hours,
            'total_hours_past'                  => $total_hours_past,
            'result_announce'                   => $result_announce,
            'comment_list'                => $comment_list  
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getMetrics($job_site, $job_portfolio)
    {
        if($job_site!=0){
            $job_portfolio_id=$job_site;       
        }else{
            $job_portfolio_id=$job_portfolio;           
        } 

        $dateRange = Carbon::now();
        

        //Get past Last Month = 4 Week Lag
        $endIniLag     = $dateRange->copy()->subWeek(4);
        $dateNow = Carbon::now();

        //Get past Last Month = 5 Week Lag
        $endPastLag     = $dateRange->copy()->subWeek(5);
        $iniPastLag     = $dateRange->copy()->subWeek(1);

         //Get past Last week = Week 1
        $endIniWeek1 = $dateNow;
        $IniWeek1 = $dateRange->copy()->subWeek(4);

        //Get past  2 week = Week 2
        $endIniWeek2 = $dateRange->copy()->subWeek(1);
        $IniWeek2 = $dateRange->copy()->subWeek(5);

        //Get past  3 week = Week 3
        $endIniWeek3 = $dateRange->copy()->subWeek(2);
        $IniWeek3 = $dateRange->copy()->subWeek(6);

        //Get past  4 week = Week 4
        $endIniWeek4 = $dateRange->copy()->subWeek(3);
        $IniWeek4 = $dateRange->copy()->subWeek(7);

        
        //Count variables
        $countEmployee=0;
        $countPortfolio=0;
        $job_portfolio=array();
        $job_site=array();
        
        $comment_list=array();

        $manager_id=0;
        
        
        //Square Feet
        $sumSquareFeet=0;        


        $job_list_str="";
        $job_list_array=array();
                            
        //Get user role
        $role_user=Auth::user()->getRole();

        $total_expense_lag=0;
        $total_bill_lag=0;
        $total_labor_tax_lag=0;
        $total_budget_monthly_lag=0;

        $total_expense_lag_past=0;
        $total_bill_lag_past=0;
        $total_labor_tax_lag_past=0;
        $total_budget_monthly_lag_past=0;

        $total_expense_week1=1;
        $total_labor_tax_week1=0;
        $total_budget_monthly_week1=0;

        $total_expense_week2=0;
        $total_labor_tax_week2=0;
        $total_budget_monthly_week2=0;

        $total_expense_week3=0;
        $total_labor_tax_week3=0;
        $total_budget_monthly_week3=0;

        $total_expense_week4=0;
        $total_labor_tax_week4=0;
        $total_budget_monthly_week4=0;

        $total_budget_monthly=0;

        $total_evaluation=0;
        $total_evaluation_no=0;
        $total_evaluation_user=0;

        $total_evaluation_past=0;
        $total_evaluation_no_past=0;

        $total_evaluation_param=array(
            'param1'    =>  0,
            'param2'    =>  0,
            'param3'    =>  0,
            'param4'    =>  0,
            'param5'    =>  0,
        );

        $total_supplies=array(
            'expense'           => 0,
            'budget_monthly'    => 0,
        );

        $total_supplies_past=array(
            'expense'           => 0,
            'budget_monthly'    => 0,
        );

        $total_salies_wages_amount=array(
            'expense'      => 0,
            'labor_tax'    => 0,
        );

        $total_salies_wages_amount_past=array(
            'expense'      => 0,
            'labor_tax'    => 0,
        );

        $total_hours=array(
            'used'       => 0,
            'budget'     => 0,
        );

        $total_hours_past=array(
            'used'       => 0,
            'budget'     => 0,
        );

        
        if($role_user==Config::get('roles.AREA_MANAGER')){
            //Role 6 - Manager


            $manager_id=Auth::user()->getManagerId();

            //List of porfolio
            $job_portfolio  = DB::table('job')
                            ->where('manager', $manager_id)
                            ->where('is_parent', 0)
                            ->get();


            //List Site                
            $job_site       = DB::table('job')
                            ->where('manager', $manager_id)
                            ->where('is_parent', 1)
                            ->get();

            //Count Employee                
            $countEmployee  = User::CountManagerEmployee($manager_id);

            //Count Portfolio
            $countPortfolio  = Job::CountPortfolio($manager_id);


             //4 Week Lag - Ini
                 $jobList = DB::table('expense')
                                    ->select(DB::raw('DISTINCT(expense.job_number) as "job_number"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $endIniLag->format('Y-m-d'))
                                    ->where('posting_date', '<=', $dateNow->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();                

                foreach ($jobList as $value) {
                    $job_list_array[]=$value->job_number;         
                }           

                $job_list_str=implode(",", $job_list_array);         


                $sumSquareFeetQuery = DB::table('job')
                                    ->select(DB::raw('SUM(square_feet) as "total_feet"'))
                                    ->whereIn('job_number', $job_list_array)
                                    ->get();

                foreach ($sumSquareFeetQuery as $value) {
                    $sumSquareFeet=$value->total_feet;         
                }


                $query_expense_lag  = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $endIniLag->format('Y-m-d'))
                                    ->where('posting_date', '<=', $dateNow->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_expense_lag as $value) {
                    $total_expense_lag=$value->total_amount;         
                }

                $query_bill_lag  = DB::table('billable_hours')
                                    ->select(DB::raw('SUM((regular_hours+overtime_hours)*pay_rate*1.19) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '>', $endIniWeek3->format('Y-m-d'))
                                    ->where('work_date', '<=', $dateNow->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_bill_lag as $value) {
                    $total_bill_lag=$value->total_amount;         
                }



                $query_labor_tax_lag  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('date', '>', $endIniLag->format('Y-m-d'))
                                    ->where('date', '<=', $dateNow->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_labor_tax_lag as $value) {
                    $total_labor_tax_lag=$value->total_amount;         
                }

                $query_budget_monthly_lag  = DB::table('budget_monthly')
                                    ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                    ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                    ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_budget_monthly_lag as $value) {
                    $total_budget_monthly_lag=$value->total_amount;         
                }

            //4 Week Lag - End

            //4 Week Lag Past - Ini

                $query_expense_lag_past= DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $endPastLag->format('Y-m-d'))
                                    ->where('posting_date', '<=', $iniPastLag->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_expense_lag_past as $value) {
                    $total_expense_lag_past=$value->total_amount;         
                }

                $query_bill_lag_past = DB::table('billable_hours')
                                    ->select(DB::raw('SUM((regular_hours+overtime_hours)*pay_rate*1.19) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '>', $endIniWeek3->format('Y-m-d'))
                                    ->where('work_date', '<=', $iniPastLag->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_bill_lag_past as $value) {
                    $total_bill_lag_past=$value->total_amount;         
                }


                $query_labor_tax_lag_past  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('date', '>', $endPastLag->format('Y-m-d'))
                                    ->where('date', '<=', $iniPastLag->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_labor_tax_lag_past as $value) {
                    $total_labor_tax_lag_past=$value->total_amount;         
                    if($total_labor_tax_lag_past==0 || $total_labor_tax_lag_past==null)
                    $total_labor_tax_lag_past=0;      
                }

                $query_budget_monthly_lag_past  = DB::table('budget_monthly')
                                    ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                    ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                    ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_budget_monthly_lag_past as $value) {
                    $total_budget_monthly_lag_past=$value->total_amount;   
                    if($total_budget_monthly_lag_past==0 || $total_budget_monthly_lag_past==null)
                        $total_budget_monthly_lag_past=0;      
                }

            //4 Week Past Lag - End    


            // Monthly budget calculation    
            $query_budget_monthly  = DB::table('budget_monthly')
                                ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                ->where('manager', $manager_id)
                                ->where('financial', 1)
                                ->where('job.job_number', $job_portfolio_id)
                                ->get();

            foreach ($query_budget_monthly as $value) {
                $total_budget_monthly=$value->total_amount;         
            }


            //Last Week = Week 1 - Ini
                
                $total_expense_week1=$total_expense_lag;        
                if($total_expense_week1==0 || $total_expense_week1==null)
                    $total_expense_week1=0;
            
                $total_labor_tax_week1=$total_labor_tax_lag;         
                if($total_labor_tax_week1==0 || $total_labor_tax_week1==null)
                    $total_labor_tax_week1=0;
                

            //Last Week = Week 1 - End

            //Week 2 - Ini  
                $total_expense_week2=$total_expense_lag_past; 
                if($total_expense_week2==0 || $total_expense_week2==null)
                    $total_expense_week2=0;        

                
                $total_labor_tax_week2=$total_labor_tax_lag_past;  
                if($total_labor_tax_week2==0 || $total_labor_tax_week2==null)
                    $total_labor_tax_week2=0;       
                

            //Week 2 - End

             //Week 3 - Ini 
                $query_expense_week3    = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek3->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek3->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_expense_week3 as $value) {
                    $total_expense_week3=$value->total_amount;  
                    if($total_expense_week3==0 || $total_expense_week3==null)
                        $total_expense_week3=0;       
                } 

                $query_labor_tax_week3  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '<=', $endIniWeek3->format('Y-m-d'))
                                    ->where('labor_tax.date', '>', $IniWeek3->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_labor_tax_week3 as $value) {
                    $total_labor_tax_week3=$value->total_amount;         
                    if($total_labor_tax_week3==0 || $total_labor_tax_week3==null)
                        $total_labor_tax_week3=0; 
                }

            //Week 3 - End

            //Week 4 - Ini 
                $query_expense_week4    = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek4->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek4->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_expense_week4 as $value) {
                    $total_expense_week4=$value->total_amount;   
                    if($total_expense_week4==0 || $total_expense_week4==null)
                        $total_expense_week4=0;         
                } 

                 $query_labor_tax_week4  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '<=', $endIniWeek4->format('Y-m-d'))
                                    ->where('labor_tax.date', '>', $IniWeek4->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_labor_tax_week4 as $value) {
                    $total_labor_tax_week4=$value->total_amount;   
                    if($total_labor_tax_week4==0 || $total_labor_tax_week4==null)
                        $total_labor_tax_week4=0;       
                }

            //Week 4 - End


            //Last Week = Supplies(Account Number=4090) Week 1 & 2 - Ini
                // Monthly budget calculation Supplies   
               $query_budget_monthly  = DB::table('budget_monthly')
                                    ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                    ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                    ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('budget_monthly.account_number', 4090)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_budget_monthly as $value) {
                    $total_supplies['budget_monthly']=$value->total_amount;         
                }

                $query_expense_week1   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('expense.account_number', 4090)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_expense_week1 as $value) {
                    $total_supplies['expense']=$value->total_amount;        
                    if($total_supplies['expense']==0 || $total_supplies['expense']==null)
                        $total_supplies['expense']=0;
                }


                $query_expense_week2   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('expense.account_number', 4090)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_expense_week2 as $value) {
                    $total_supplies_past['expense']=$value->total_amount;        
                    if($total_supplies_past['expense']==0 || $total_supplies_past['expense']==null)
                        $total_supplies_past['expense']=0;
                }


            //Last Week = Week 1 & 2 Supplies  - End    


             //Last Week = Labor & Tax(Account Number=4000, 4275, 4278, 4277, 4280, 4276 ) Week 1 & 2 - Ini
                // Monthly budget calculation Supplies   
                $query_labor_tax_week1  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('labor_tax.date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_labor_tax_week1 as $value) {
                    $total_salies_wages_amount['labor_tax']=$value->total_amount;         
                    if($total_salies_wages_amount['labor_tax']==0 || $total_salies_wages_amount['labor_tax']==null)
                        $total_salies_wages_amount['labor_tax']=0;
                }

                //4010, 4020

                $query_expense_week1   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->whereIn('expense.account_number', [4000, 4001, 4003, 4004, 4005, 4007, 4010, 4011, 4020, 4275, 4276, 4277, 4278, 4279, 4280])
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_expense_week1 as $value) {
                    $total_salies_wages_amount['expense']=$value->total_amount;        
                    if($total_salies_wages_amount['expense']==0 || $total_salies_wages_amount['expense']==null)
                        $total_salies_wages_amount['expense']=0;
                }

                 $query_labor_tax_week2  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('labor_tax.date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_labor_tax_week2 as $value) {
                    $total_salies_wages_amount_past['labor_tax']=$value->total_amount;         
                    if($total_salies_wages_amount_past['labor_tax']==0 || $total_salies_wages_amount_past['labor_tax']==null)
                        $total_salies_wages_amount_past['labor_tax']=0;
                }


                $query_expense_week2   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->whereIn('expense.account_number', [4000, 4001, 4003, 4004, 4005, 4007, 4010, 4011, 4020, 4275, 4276, 4277, 4278, 4279, 4280])
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_expense_week2 as $value) {
                    $total_salies_wages_amount_past['expense']=$value->total_amount;        
                    if($total_salies_wages_amount_past['expense']==0 || $total_salies_wages_amount_past['expense']==null)
                        $total_salies_wages_amount_past['expense']=0;
                }


            //Last Week = Week 1 & 2 Supplies  - End       
   
            //Last Week =  Budget - Billable Hours - Ini
               $query_hours  = DB::table('billable_hours')
                                    ->select(DB::raw('SUM(regular_hours) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('work_date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours['used']=$value->total_amount;         
                }

                $query_hours  = DB::table('budget')
                                    ->select(DB::raw('SUM(hours) as "total_amount"'))
                                    ->join('job', 'budget.job_number', '=', 'job.job_number')
                                    ->where('date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours['budget']=$value->total_amount;         
                }

            //Last Week =  Budget - Billable Hours - End 

            //Past Week =  Budget - Billable Hours - Ini
               $query_hours  = DB::table('billable_hours')
                                    ->select(DB::raw('SUM(regular_hours) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('work_date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours_past['used']=$value->total_amount;         
                }

                $query_hours  = DB::table('budget')
                                    ->select(DB::raw('SUM(hours) as "total_amount"'))
                                    ->join('job', 'budget.job_number', '=', 'job.job_number')
                                    ->where('date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('job.job_number', $job_portfolio_id)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours_past['budget']=$value->total_amount;         
                }

            //Past Week =  Budget - Billable Hours - End     
        
        }

        //Normalize data

        if($sumSquareFeet==0)
            $sumSquareFeet=1;
    

        //New Calculations
        $calc_val=$total_budget_monthly_lag+$total_labor_tax_lag;
        if($calc_val==0)
            $calc_val=1;
        $lag_differencial=(($total_expense_lag+$total_bill_lag)*100)/($calc_val);

        $budget_cant=$total_budget_monthly_lag_past+$total_labor_tax_lag_past;
        if($budget_cant==0)
            $budget_cant=1;
        $lag_differencial_past=(($total_expense_lag_past+$total_bill_lag_past)*100)/($budget_cant);



        $expense_calc_week1=$total_expense_week1;
        $expense_calc_week2=$total_expense_week2;
        if($expense_calc_week2==0 || $expense_calc_week2==null)
            $expense_calc_week2=1;
        
        $cost_past_week=$lag_differencial_past-$lag_differencial;

        $expense_week1=$total_expense_week1+$total_bill_lag;
        $expense_week2=$total_expense_week2+$total_bill_lag_past;
        $expense_week3=$total_expense_week3;
        $expense_week4=$total_expense_week4;

        $budget_week1=$total_budget_monthly+$total_labor_tax_week1;
        $budget_week2=$total_budget_monthly+$total_labor_tax_week2;
        $budget_week3=$total_budget_monthly+$total_labor_tax_week3;
        $budget_week4=$total_budget_monthly+$total_labor_tax_week4;


        $budget_cant=$budget_week1;
        if($budget_cant==0)
            $budget_cant=1;
        $lag_past_week=($expense_week1*100)/($budget_cant);

        //Announcement list
        switch ($role_user) {
            case '4':
                $permission_filter='1____';
                break;
            case '6':
                $permission_filter='_1___';
                break;
            case '5':
                $permission_filter='__1__';
                break;
            case '8':
                $permission_filter='___1_';
                break;
            case '9':
                $permission_filter='____1';
                break;
            default:
                $permission_filter='2____';
                break;
        }
        $result_announce = DB::table('announce')
                    ->select(DB::raw('*'))
                    ->where('status', 1)
                    ->where('permission', 'like' , $permission_filter)
                    ->orderBy('closing_date', 'desc')
                    ->paginate(50);


        return view('metrics.page',  [
            'user_id'                           => 0,
            'count_employee'                    => $countEmployee,
            'count_portfolio'                   => $countPortfolio,  
            'count_evaluation'                  => 0,
            'sum_square_feet'                   => $sumSquareFeet,
            'role_user'                         => $role_user,
            'job_portfolio'                     => $job_portfolio,
            'job_site'                          => $job_site , 
            'job_list_str'                      => $job_list_str,
            'job_portfolio_id'                  => $job_portfolio_id,
            'manager_id'                        => $manager_id,
            'total_expense_lag'                 => $total_expense_lag,
            'total_labor_tax_lag'               => $total_labor_tax_lag,
            'total_budget_monthly_lag'          => $total_budget_monthly_lag,
            'total_bill_lag'                    => $total_bill_lag,
            'cost_past_week'                    => $cost_past_week,
            'lag_differencial'                  => $lag_differencial,
            'lag_differencial_past'             => $lag_differencial_past,
            'lag_past_week'                     => $lag_past_week,
            'expense_week1'                     => $expense_week1,
            'expense_week2'                     => $expense_week2,
            'expense_week3'                     => $expense_week3,
            'expense_week4'                     => $expense_week4,
            'budget_week1'                      => $budget_week1,
            'budget_week2'                      => $budget_week2,
            'budget_week3'                      => $budget_week3,
            'budget_week4'                      => $budget_week4,
            'total_evaluation'                  => $total_evaluation,
            'total_evaluation_no'               => $total_evaluation_no,
            'total_evaluation_past'             => $total_evaluation_past,
            'total_evaluation_no_past'          => $total_evaluation_no_past,
            'total_evaluation_user'             => $total_evaluation_user,
            'total_evaluation_param'            =>  $total_evaluation_param,
            'total_supplies'                    =>  $total_supplies,
            'total_supplies_past'               =>  $total_supplies_past,
            'total_salies_wages_amount'         =>  $total_salies_wages_amount,
            'total_salies_wages_amount_past'    =>  $total_salies_wages_amount_past,
            'total_hours'                       =>  $total_hours,
            'total_hours_past'                  =>  $total_hours_past,
            'result_announce'                   =>  $result_announce,
            'comment_list'                      =>  $comment_list,
        ]);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getMetricsManager($manager_id)
    {
        $dateRange = Carbon::now();
        

        //Get past Last Month = 4 Week Lag
        $endIniLag     = $dateRange->copy()->subWeek(4);
        $dateNow = Carbon::now();

        //Get past Last Month = 5 Week Lag
        $endPastLag     = $dateRange->copy()->subWeek(5);
        $iniPastLag     = $dateRange->copy()->subWeek(1);

         //Get past Last week = Week 1
        $endIniWeek1 = $dateNow;
        $IniWeek1 = $dateRange->copy()->subWeek(4);

        //Get past  2 week = Week 2
        $endIniWeek2 = $dateRange->copy()->subWeek(1);
        $IniWeek2 = $dateRange->copy()->subWeek(5);

        //Get past  3 week = Week 3
        $endIniWeek3 = $dateRange->copy()->subWeek(2);
        $IniWeek3 = $dateRange->copy()->subWeek(6);

        //Get past  4 week = Week 4
        $endIniWeek4 = $dateRange->copy()->subWeek(3);
        $IniWeek4 = $dateRange->copy()->subWeek(7);

        
        //Count variables
        $countEmployee=0;
        $countPortfolio=0;
        $job_portfolio=array();
        $job_site=array();
        $manager_list=array();

        $comment_list=array();
        
        
        //Square Feet
        $sumSquareFeet=0;        


        $job_list_str="";
        $job_list_array=array();
                            
        //Get user role
        $role_user=Auth::user()->getRole();

        $total_expense_lag=0;
        $total_labor_tax_lag=0;
        $total_bill_lag=0;
        $total_budget_monthly_lag=0;

        $total_expense_lag_past=0;
        $total_bill_lag_past=0;
        $total_labor_tax_lag_past=0;
        $total_budget_monthly_lag_past=0;

        $total_expense_week1=1;
        $total_labor_tax_week1=0;
        $total_budget_monthly_week1=0;

        $total_expense_week2=0;
        $total_labor_tax_week2=0;
        $total_budget_monthly_week2=0;

        $total_expense_week3=0;
        $total_labor_tax_week3=0;
        $total_budget_monthly_week3=0;

        $total_expense_week4=0;
        $total_labor_tax_week4=0;
        $total_budget_monthly_week4=0;

        $total_budget_monthly=0;

        $total_evaluation=0;
        $total_evaluation_no=0;
        $total_evaluation_user=0;

        $total_evaluation_past=0;
        $total_evaluation_no_past=0;

        $total_evaluation_param=array(
            'param1'    =>  0,
            'param2'    =>  0,
            'param3'    =>  0,
            'param4'    =>  0,
            'param5'    =>  0,
        );

        $total_supplies=array(
            'expense'           => 0,
            'budget_monthly'    => 0,
        );

        $total_supplies_past=array(
            'expense'           => 0,
            'budget_monthly'    => 0,
        );

        $total_salies_wages_amount=array(
            'expense'      => 0,
            'labor_tax'    => 0,
        );

        $total_salies_wages_amount_past=array(
            'expense'      => 0,
            'labor_tax'    => 0,
        );

        $total_hours=array(
            'used'       => 0,
            'budget'     => 0,
        );

        $total_hours_past=array(
            'used'       => 0,
            'budget'     => 0,
        );


            //List of porfolio
            $job_portfolio  = DB::table('job')
                            ->where('manager', $manager_id)
                            ->where('is_parent', 0)
                            ->get();


            //List Site                
            $job_site       = DB::table('job')
                            ->where('manager', $manager_id)
                            ->where('is_parent', 1)
                            ->get();

            //Count Employee                
            $countEmployee  = User::CountManagerEmployee($manager_id);

            //Count Portfolio
            $countPortfolio  = Job::CountPortfolio($manager_id);


             //4 Week Lag - Ini
                 $jobList = DB::table('expense')
                                    ->select(DB::raw('DISTINCT(expense.job_number) as "job_number"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $endIniLag->format('Y-m-d'))
                                    ->where('posting_date', '<=', $dateNow->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();                

                foreach ($jobList as $value) {
                    $job_list_array[]=$value->job_number;         
                }           

                $job_list_str=implode(",", $job_list_array);         


                $sumSquareFeetQuery = DB::table('job')
                                    ->select(DB::raw('SUM(square_feet) as "total_feet"'))
                                    ->whereIn('job_number', $job_list_array)
                                    ->get();

                foreach ($sumSquareFeetQuery as $value) {
                    $sumSquareFeet=$value->total_feet;         
                }


                $query_expense_lag  = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $endIniLag->format('Y-m-d'))
                                    ->where('posting_date', '<=', $dateNow->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_expense_lag as $value) {
                    $total_expense_lag=$value->total_amount;         
                }

                $query_bill_lag  = DB::table('billable_hours')
                                    ->select(DB::raw('SUM((regular_hours+overtime_hours)*pay_rate*1.19) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '>', $endIniWeek3->format('Y-m-d'))
                                    ->where('work_date', '<=', $dateNow->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->get();

                foreach ($query_bill_lag as $value) {
                    $total_bill_lag=$value->total_amount;         
                }


                $query_labor_tax_lag  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('date', '>', $endIniLag->format('Y-m-d'))
                                    ->where('date', '<=', $dateNow->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_lag as $value) {
                    $total_labor_tax_lag=$value->total_amount;         
                }

                $query_budget_monthly_lag  = DB::table('budget_monthly')
                                    ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                    ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                    ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_budget_monthly_lag as $value) {
                    $total_budget_monthly_lag=$value->total_amount;         
                }

            //4 Week Lag - End

            //4 Week Lag Past - Ini

                $query_expense_lag_past= DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '>', $endPastLag->format('Y-m-d'))
                                    ->where('posting_date', '<=', $iniPastLag->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_expense_lag_past as $value) {
                    $total_expense_lag_past=$value->total_amount;         
                }

                $query_bill_lag_past = DB::table('billable_hours')
                                    ->select(DB::raw('SUM((regular_hours+overtime_hours)*pay_rate*1.19) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '>', $endIniWeek3->format('Y-m-d'))
                                    ->where('work_date', '<=', $iniPastLag->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->get();

                foreach ($query_bill_lag_past as $value) {
                    $total_bill_lag_past=$value->total_amount;         
                }


                $query_labor_tax_lag_past  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('date', '>', $endPastLag->format('Y-m-d'))
                                    ->where('date', '<=', $iniPastLag->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_lag_past as $value) {
                    $total_labor_tax_lag_past=$value->total_amount;         
                    if($total_labor_tax_lag_past==0 || $total_labor_tax_lag_past==null)
                    $total_labor_tax_lag_past=0;      
                }

                

                $query_budget_monthly_lag_past  = DB::table('budget_monthly')
                                    ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                    ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                    ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_budget_monthly_lag_past as $value) {
                    $total_budget_monthly_lag_past=$value->total_amount;   
                    if($total_budget_monthly_lag_past==0 || $total_budget_monthly_lag_past==null)
                        $total_budget_monthly_lag_past=0;      
                }

            //4 Week Past Lag - End    


            // Monthly budget calculation    
            $query_budget_monthly  = DB::table('budget_monthly')
                                ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                ->where('manager', $manager_id)
                                ->where('financial', 1)
                                ->get();

            foreach ($query_budget_monthly as $value) {
                $total_budget_monthly=$value->total_amount;         
            }


            //Last Week = Week 1 - Ini
                $total_expense_week1=$total_expense_lag;        
                if($total_expense_week1==0 || $total_expense_week1==null)
                    $total_expense_week1=0;
                
                $total_labor_tax_week1=$total_labor_tax_lag;         
                if($total_labor_tax_week1==0 || $total_labor_tax_week1==null)
                    $total_labor_tax_week1=0;
                
            //Last Week = Week 1 - End

            //Week 2 - Ini
                $total_expense_week2=$total_expense_lag_past; 
                if($total_expense_week2==0 || $total_expense_week2==null)
                    $total_expense_week2=0;        
                
                $total_labor_tax_week2=$total_labor_tax_lag_past;  
                if($total_labor_tax_week2==0 || $total_labor_tax_week2==null)
                    $total_labor_tax_week2=0;       
                
            //Week 2 - End

             //Week 3 - Ini 
                $query_expense_week3    = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek3->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek3->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_expense_week3 as $value) {
                    $total_expense_week3=$value->total_amount;  
                    if($total_expense_week3==0 || $total_expense_week3==null)
                        $total_expense_week3=0;       
                } 

                $query_labor_tax_week3  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '<=', $endIniWeek3->format('Y-m-d'))
                                    ->where('labor_tax.date', '>', $IniWeek3->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_week3 as $value) {
                    $total_labor_tax_week3=$value->total_amount;         
                    if($total_labor_tax_week3==0 || $total_labor_tax_week3==null)
                        $total_labor_tax_week3=0; 
                }

            //Week 3 - End

            //Week 4 - Ini 
                $query_expense_week4    = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek4->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek4->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_expense_week4 as $value) {
                    $total_expense_week4=$value->total_amount;   
                    if($total_expense_week4==0 || $total_expense_week4==null)
                        $total_expense_week4=0;         
                } 

                 $query_labor_tax_week4  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '<=', $endIniWeek4->format('Y-m-d'))
                                    ->where('labor_tax.date', '>', $IniWeek4->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_week4 as $value) {
                    $total_labor_tax_week4=$value->total_amount;   
                    if($total_labor_tax_week4==0 || $total_labor_tax_week4==null)
                        $total_labor_tax_week4=0;       
                }

            //Week 4 - End


            //Last Week = Supplies(Account Number=4090) Week 1 & 2 - Ini
                // Monthly budget calculation Supplies   
               $query_budget_monthly  = DB::table('budget_monthly')
                                    ->select(DB::raw('SUM(((monthly_budget*12)/52)*4) as "total_amount"'))
                                    ->join('account', 'budget_monthly.account_number', '=', 'account.account_number')
                                    ->join('job', 'budget_monthly.job_number', '=', 'job.job_number')
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('budget_monthly.account_number', 4090)
                                    ->get();

                foreach ($query_budget_monthly as $value) {
                    $total_supplies['budget_monthly']=$value->total_amount;         
                }

                $query_expense_week1   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('expense.account_number', 4090)
                                    ->get();

                foreach ($query_expense_week1 as $value) {
                    $total_supplies['expense']=$value->total_amount;        
                    if($total_supplies['expense']==0 || $total_supplies['expense']==null)
                        $total_supplies['expense']=0;
                }


                $query_expense_week2   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->where('expense.account_number', 4090)
                                    ->get();

                foreach ($query_expense_week2 as $value) {
                    $total_supplies_past['expense']=$value->total_amount;        
                    if($total_supplies_past['expense']==0 || $total_supplies_past['expense']==null)
                        $total_supplies_past['expense']=0;
                }


            //Last Week = Week 1 & 2 Supplies  - End    


             //Last Week = Labor & Tax(Account Number=4000, 4275, 4278, 4277, 4280, 4276 ) Week 1 & 2 - Ini
                // Monthly budget calculation Supplies   
                $query_labor_tax_week1  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('labor_tax.date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_week1 as $value) {
                    $total_salies_wages_amount['labor_tax']=$value->total_amount;         
                    if($total_salies_wages_amount['labor_tax']==0 || $total_salies_wages_amount['labor_tax']==null)
                        $total_salies_wages_amount['labor_tax']=0;
                }

                $query_expense_week1   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->whereIn('expense.account_number', [4000, 4001, 4003, 4004, 4005, 4007, 4010, 4011, 4020, 4275, 4276, 4277, 4278, 4279, 4280])
                                    ->get();

                foreach ($query_expense_week1 as $value) {
                    $total_salies_wages_amount['expense']=$value->total_amount;        
                    if($total_salies_wages_amount['expense']==0 || $total_salies_wages_amount['expense']==null)
                        $total_salies_wages_amount['expense']=0;
                }

                 $query_labor_tax_week2  = DB::table('labor_tax')
                                    ->select(DB::raw('SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) as "total_amount"'))
                                    ->join('account', 'labor_tax.account_number', '=', 'account.account_number')
                                    ->join('job', 'labor_tax.job_number', '=', 'job.job_number')
                                    ->where('labor_tax.date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('labor_tax.date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->get();

                foreach ($query_labor_tax_week2 as $value) {
                    $total_salies_wages_amount_past['labor_tax']=$value->total_amount;         
                    if($total_salies_wages_amount_past['labor_tax']==0 || $total_salies_wages_amount_past['labor_tax']==null)
                        $total_salies_wages_amount_past['labor_tax']=0;
                }


                $query_expense_week2   = DB::table('expense')
                                    ->select(DB::raw('SUM(amount) as "total_amount"'))
                                    ->join('account', 'expense.account_number', '=', 'account.account_number')
                                    ->join('job', 'expense.job_number', '=', 'job.job_number')
                                    ->where('posting_date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('posting_date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->where('financial', 1)
                                    ->whereIn('expense.account_number', [4000, 4001, 4003, 4004, 4005, 4007, 4010, 4011, 4020, 4275, 4276, 4277, 4278, 4279, 4280])
                                    ->get();

                foreach ($query_expense_week2 as $value) {
                    $total_salies_wages_amount_past['expense']=$value->total_amount;        
                    if($total_salies_wages_amount_past['expense']==0 || $total_salies_wages_amount_past['expense']==null)
                        $total_salies_wages_amount_past['expense']=0;
                }


            //Last Week = Week 1 & 2 Supplies  - End     


            //Last Week =  Budget - Billable Hours - Ini
               $query_hours  = DB::table('billable_hours')
                                    ->select(DB::raw('SUM(regular_hours) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('work_date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours['used']=$value->total_amount;         
                }

                $query_hours  = DB::table('budget')
                                    ->select(DB::raw('SUM(hours) as "total_amount"'))
                                    ->join('job', 'budget.job_number', '=', 'job.job_number')
                                    ->where('date', '<=', $endIniWeek1->format('Y-m-d'))
                                    ->where('date', '>', $IniWeek1->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours['budget']=$value->total_amount;         
                }

            //Last Week =  Budget - Billable Hours - End 

                //Past Week =  Budget - Billable Hours - Ini
               $query_hours  = DB::table('billable_hours')
                                    ->select(DB::raw('SUM(regular_hours) as "total_amount"'))
                                    ->join('job', 'billable_hours.job_number', '=', 'job.job_number')
                                    ->where('work_date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('work_date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours_past['used']=$value->total_amount;         
                }

                $query_hours  = DB::table('budget')
                                    ->select(DB::raw('SUM(hours) as "total_amount"'))
                                    ->join('job', 'budget.job_number', '=', 'job.job_number')
                                    ->where('date', '<=', $endIniWeek2->format('Y-m-d'))
                                    ->where('date', '>', $IniWeek2->format('Y-m-d'))
                                    ->where('manager', $manager_id)
                                    ->get();

                foreach ($query_hours as $value) {
                    $total_hours_past['budget']=$value->total_amount;         
                }

            //Past Week =  Budget - Billable Hours - End  
   

            
            /*Evaluation functions - INI*/
                $user_id=0;
                $user = DB::table('users')
                            ->where('manager_id', $manager_id)
                            ->where('active', 1)
                            ->get();

                foreach ($user as $user_item) {
                    $user_id = $user_item->id;
                }


                $query_evaluation  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1)+SUM(parameter2)+SUM(parameter3)+SUM(parameter4)+SUM(parameter5) as "total_amount"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', $user_id)
                                        ->get();

                foreach ($query_evaluation as $value) {
                    $total_evaluation=$value->total_amount;         
                }

                $query_evaluation  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1) as "param1", SUM(parameter2) as "param2", SUM(parameter3) as "param3", SUM(parameter4) as "param4",SUM(parameter5) as "param5"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', $user_id)
                                        ->get();

                foreach ($query_evaluation as $value) {
                    $total_evaluation_param['param1']=$value->param1;
                    $total_evaluation_param['param2']=$value->param2;
                    $total_evaluation_param['param3']=$value->param3;         
                    $total_evaluation_param['param4']=$value->param4;
                    $total_evaluation_param['param5']=$value->param5;

                }

                $query_evaluation_past  = DB::table('evaluation')
                                        ->select(DB::raw('SUM(parameter1)+SUM(parameter2)+SUM(parameter3)+SUM(parameter4)+SUM(parameter5) as "total_amount"'))
                                        ->where('created_at', '>', $endPastLag)
                                        ->where('created_at', '<=', $iniPastLag)
                                        ->where('evaluate_user_id', $user_id)
                                        ->get();

                foreach ($query_evaluation_past as $value) {
                    $total_evaluation_past=$value->total_amount;         
                }


                //Number of evaluations
                $total_evaluation_no  = DB::table('evaluation')
                                        ->select(DB::raw('id'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('evaluate_user_id', $user_id)
                                        ->count();

                //Number of evaluations Past
                $total_evaluation_no_past = DB::table('evaluation')
                                        ->select(DB::raw('id'))
                                        ->where('created_at', '>', $endPastLag)
                                        ->where('created_at', '<=', $iniPastLag)
                                        ->where('evaluate_user_id', $user_id)
                                        ->count();

                //Number of users evaluated                        
                $query_evaluation_user  = DB::table('evaluation')
                                        ->select(DB::raw('COUNT(DISTINCT(evaluate_user_id)) as "total"'))
                                        ->where('created_at', '>', $endIniLag)
                                        ->where('created_at', '<=', $dateNow)
                                        ->where('user_id', $user_id)
                                        ->get();

                foreach ($query_evaluation_user as $value) {
                    $total_evaluation_user=$value->total;         
                }



            $manager_list   = DB::table('users')
                            -> where('manager_id', '!=', 0)
                            -> where('active', 1)
                            -> orderBy('manager_id', 'asc')
                            -> get();

        //Normalize data

        if($sumSquareFeet==0)
            $sumSquareFeet=1;
    

        //New Calculations
        $calc_val=$total_budget_monthly_lag+$total_labor_tax_lag;
        if($calc_val==0)
            $calc_val=1;
        $lag_differencial=(($total_expense_lag+$total_bill_lag)*100)/($calc_val);

        $budget_cant=$total_budget_monthly_lag_past+$total_labor_tax_lag_past;
        if($budget_cant==0)
            $budget_cant=1;
        $lag_differencial_past=(($total_expense_lag_past+$total_bill_lag_past)*100)/($budget_cant);



        $expense_calc_week1=$total_expense_week1;
        $expense_calc_week2=$total_expense_week2;
        if($expense_calc_week2==0 || $expense_calc_week2==null)
            $expense_calc_week2=1;
        
        $cost_past_week=$lag_differencial_past-$lag_differencial;

        $expense_week1=$total_expense_week1+$total_bill_lag;
        $expense_week2=$total_expense_week2+$total_bill_lag_past;
        $expense_week3=$total_expense_week3;
        $expense_week4=$total_expense_week4;

        $budget_week1=$total_budget_monthly+$total_labor_tax_week1;
        $budget_week2=$total_budget_monthly+$total_labor_tax_week2;
        $budget_week3=$total_budget_monthly+$total_labor_tax_week3;
        $budget_week4=$total_budget_monthly+$total_labor_tax_week4;


        $budget_cant=$budget_week1;
        if($budget_cant==0)
            $budget_cant=1;
        $lag_past_week=($expense_week1*100)/($budget_cant);

        //$cost_past_week=$total_expense_week1;

        switch ($role_user) {
            case '4':
                $permission_filter='1____';
                break;
            case '6':
                $permission_filter='_1___';
                break;
            case '5':
                $permission_filter='__1__';
                break;
            case '8':
                $permission_filter='___1_';
                break;
            case '9':
                $permission_filter='____1';
                break;
            default:
                $permission_filter='2____';
                break;
        }
        
        $result_announce = DB::table('announce')
                    ->select(DB::raw('*'))
                    ->where('status', 1)
                    ->where('permission', 'like' , $permission_filter)
                    ->orderBy('closing_date', 'desc')
                    ->paginate(50);

        return view('metrics.page', [
            'user_id'                           => $user_id, 
            'count_employee'                    => $countEmployee,
            'count_portfolio'                   => $countPortfolio,  
            'count_evaluation'                  => 0,
            'sum_square_feet'                   => $sumSquareFeet,
            'role_user'                         => $role_user,
            'manager_list'                      => $manager_list,  
            'job_portfolio'                     => $job_portfolio,
            'job_site'                          => $job_site, 
            'job_list_str'                      => $job_list_str,
            'job_portfolio_id'                  => 0,
            'manager_id'                        => $manager_id,
            'total_expense_lag'                 => $total_expense_lag,
            'total_labor_tax_lag'               => $total_labor_tax_lag,
            'total_budget_monthly_lag'          => $total_budget_monthly_lag,
            'total_bill_lag'                    => $total_bill_lag,
            'cost_past_week'                    => $cost_past_week,
            'lag_differencial'                  => $lag_differencial,
            'lag_differencial_past'             => $lag_differencial_past,
            'lag_past_week'                     => $lag_past_week,
            'expense_week1'                     => $expense_week1,
            'expense_week2'                     => $expense_week2,
            'expense_week3'                     => $expense_week3,
            'expense_week4'                     => $expense_week4,
            'budget_week1'                      => $budget_week1,
            'budget_week2'                      => $budget_week2,
            'budget_week3'                      => $budget_week3,
            'budget_week4'                      => $budget_week4,
            'total_evaluation'                  => $total_evaluation,
            'total_evaluation_no'               => $total_evaluation_no,
            'total_evaluation_past'             => $total_evaluation_past,
            'total_evaluation_no_past'          => $total_evaluation_no_past,
            'total_evaluation_user'             => $total_evaluation_user,
            'total_evaluation_param'            => $total_evaluation_param,
            'total_supplies'                    => $total_supplies,
            'total_supplies_past'               => $total_supplies_past,
            'total_salies_wages_amount'         => $total_salies_wages_amount,
            'total_salies_wages_amount_past'    => $total_salies_wages_amount_past,
            'total_hours'                       => $total_hours,
            'total_hours_past'                  => $total_hours_past,
            'result_announce'                   => $result_announce,
            'comment_list'                      => $comment_list
        ]);
    }

}
