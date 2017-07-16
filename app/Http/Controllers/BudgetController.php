<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;


use DB;
use Auth;
use Response;
use App\Job;
use Excel;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex(Request $request)
    {
        $bg_ini_range = $request->session()->get('rpt_ini_date', function() {
            return '1';
        });

        $bg_end_range = $request->session()->get('rpt_end_date', function() {
            return date('m');
        });

        return view('budget.home', ['month_ini' =>  $bg_ini_range, 'month_end'  =>  $bg_end_range] );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function report(Request $request)
    {die("12341234");
        $month=10;
        $ini_range=1;
        $end_range=4;

        $ini_range = $request->session()->get('rpt_ini_date', function() {
            return '1';
        });

        $end_range = $request->session()->get('rpt_end_date', function() {
            return '12';
        });

        //Year
        $bg_year = $request->session()->get('rpt_year', function() {
            return '0';
        });

         //Calculate values
        $ini_range=$ini_range+$bg_year;
        $end_range=$end_range+$bg_year;

        $month=($end_range-$ini_range)+1;

        $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description,
                                    (SELECT -SUM(actual_gl.actual_total) FROM actual_gl WHERE actual_gl.job_number=job.job_number AND actual_gl.period >='.$ini_range.' AND actual_gl.period <='.$end_range.') as "at_total",
                                    (SELECT -SUM(budget_data.total) FROM budget_data WHERE budget_data.job_number=job.job_number AND budget_data.period >='.$ini_range.' AND budget_data.period <='.$end_range.') as "bg_total",
                                    (SELECT -(budget_gl.period1*'.$month.') FROM budget_gl WHERE budget_gl.job_number=job.job_number AND budget_gl.period ='.$bg_year.') as "bg_total_job"'))  
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->groupBy('job.job_number')
                  ->groupBy('job.job_description')
                  ->orderBy('job.job_number', 'asc')
                  ->get();

        $year_array=array(
          0 => '2016',
          12 => '2017',
         );        


        return view('budget.report',
          ['list'         =>  $list,
          'ini_range'     =>  ($ini_range-$bg_year),
          'end_range'     =>  ($end_range-$bg_year),
          'year'          =>  $year_array[$bg_year],
          ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function graphs(Request $request)
    {
        $month=10;

        $ini_range = $request->session()->get('rpt_ini_date', function() {
            return '1';
        });

        $end_range = $request->session()->get('rpt_end_date', function() {
            return date('m');
        });

        $month=($end_range-$ini_range)+1;


        $sum=array();
        for ($i=1; $i <=10 ; $i++) { 
          if($i!=2){
            $sum[$i]=DB::table('job')
                  ->select(DB::raw('(SELECT -SUM(budget_data.total) FROM budget_data WHERE budget_data.job_number=job.job_number AND budget_data.period >='.$ini_range.' AND budget_data.period <='.$end_range.') as "total",
                                    -(budget_gl.period1*'.$month.') as "total_job"'))  
                  ->leftJoin('budget_gl', 'job.job_number' , '=' , 'budget_gl.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('job.division', $i)
                  ->groupBy('job.job_number')
                  ->groupBy('job.job_description')
                  ->orderBy('job.job_number', 'asc')
                  ->get();  
          } 
        }  

        $tk_sum=array();        
        for ($i=1; $i <=10 ; $i++) { 
          if($i!=2){
            $tk_sum[$i]=DB::table('job')
                  ->select(DB::raw('(SELECT -SUM(actual_gl.actual_total) FROM actual_gl WHERE actual_gl.job_number=job.job_number AND actual_gl.period >='.$ini_range.' AND actual_gl.period <='.$end_range.') as "total"'))  
                  ->leftJoin('budget_gl', 'job.job_number' , '=' , 'budget_gl.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('job.division', $i)
                  ->groupBy('job.job_number')
                  ->groupBy('job.job_description')
                  ->orderBy('job.job_number', 'asc')
                  ->get();  
          } 
        }  
        
        

        return view('budget.graphs',
          [
          'sum'     =>  $sum,
          'tk_sum'  =>  $tk_sum,
          'ini_range' =>  $ini_range,
          'end_range' =>  $end_range,
          ] 
        );
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function loadList(Request $request)
    {
        $data=$request->all(); 
        $month=10;
        $ini_range=1;
        $end_range=10;

        $ini_range = $request->session()->get('rpt_ini_date', function() {
            return '1';
        });

        $end_range = $request->session()->get('rpt_end_date', function() {
            return '12';
        });

        //Year
        $bg_year = $request->session()->get('rpt_year', function() {
            return '0';
        });

        //Calculate values
        $ini_range=$ini_range+$bg_year;
        $end_range=$end_range+$bg_year;

        $month=($end_range-$ini_range)+1;


        if($data['industry']!=0){

           $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description,
                                    (SELECT -SUM(actual_gl.actual_total) FROM actual_gl WHERE actual_gl.job_number=job.job_number AND actual_gl.period >='.$ini_range.' AND actual_gl.period <='.$end_range.') as "at_total",
                                    (SELECT -SUM(budget_data.total) FROM budget_data WHERE budget_data.job_number=job.job_number AND budget_data.period >='.$ini_range.' AND budget_data.period <='.$end_range.') as "bg_total",
                                    (SELECT -(budget_gl.period1*'.$month.') FROM budget_gl WHERE budget_gl.job_number=job.job_number AND budget_gl.period ='.$bg_year.') as "bg_total_job"'))  
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('job.division', $data['industry'])
                  ->groupBy('job.job_number')
                  ->groupBy('job.job_description')
                  ->orderBy('job.job_number', 'asc')
                  ->get();             
         
        }else{
            
          $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description,
                                    (SELECT -SUM(actual_gl.actual_total) FROM actual_gl WHERE actual_gl.job_number=job.job_number AND actual_gl.period >='.$ini_range.' AND actual_gl.period <='.$end_range.') as "at_total",
                                    (SELECT -SUM(budget_data.total) FROM budget_data WHERE budget_data.job_number=job.job_number AND budget_data.period >='.$ini_range.' AND budget_data.period <='.$end_range.') as "bg_total",
                                    (SELECT -(budget_gl.period1*'.$month.') FROM budget_gl WHERE budget_gl.job_number=job.job_number AND budget_gl.period ='.$bg_year.') as "bg_total_job"'))  
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->groupBy('job.job_number')
                  ->groupBy('job.job_description')
                  ->orderBy('job.job_number', 'asc')
                  ->get();

        }

        $html=view('budget.report_load',
                ['list'   => $list,
                'ini_range' =>  $ini_range,
                'end_range' =>  $end_range]
                )->render();


         return Response::json(
            array('status'        => '1', 'html'  => $html)
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function reportExport(Request $request, $type)
    {
        $month=10;
        $ini_range=1;
        $end_range=10;

        $ini_range = $request->session()->get('rpt_ini_date', function() {
            return '1';
        });

        $end_range = $request->session()->get('rpt_end_date', function() {
            return date('m');
        });

        //Year
        $bg_year = $request->session()->get('rpt_year', function() {
            return '0';
        });

        //Calculate values
        $ini_range=$ini_range+$bg_year;
        $end_range=$end_range+$bg_year;

        $month=($end_range-$ini_range)+1;


        if($type!=0){

           $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description,
                                    (SELECT -SUM(actual_gl.actual_total) FROM actual_gl WHERE actual_gl.job_number=job.job_number AND actual_gl.period >='.$ini_range.' AND actual_gl.period <='.$end_range.') as "at_total",
                                    (SELECT -SUM(budget_data.total) FROM budget_data WHERE budget_data.job_number=job.job_number AND budget_data.period >='.$ini_range.' AND budget_data.period <='.$end_range.') as "bg_total",
                                    (SELECT -(budget_gl.period1*'.$month.') FROM budget_gl WHERE budget_gl.job_number=job.job_number AND budget_gl.period ='.$bg_year.') as "bg_total_job"'))  
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('job.division', $type)
                  ->groupBy('job.job_number')
                  ->groupBy('job.job_description')
                  ->orderBy('job.job_number', 'asc')
                  ->get();             
         
        }else{
            
          $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description,
                                    (SELECT -SUM(actual_gl.actual_total) FROM actual_gl WHERE actual_gl.job_number=job.job_number AND actual_gl.period >='.$ini_range.' AND actual_gl.period <='.$end_range.') as "at_total",
                                    (SELECT -SUM(budget_data.total) FROM budget_data WHERE budget_data.job_number=job.job_number AND budget_data.period >='.$ini_range.' AND budget_data.period <='.$end_range.') as "bg_total",
                                    (SELECT -(budget_gl.period1*'.$month.') FROM budget_gl WHERE budget_gl.job_number=job.job_number AND budget_gl.period ='.$bg_year.') as "bg_total_job"'))  
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->groupBy('job.job_number')
                  ->groupBy('job.job_description')
                  ->orderBy('job.job_number', 'asc')
                  ->get();

        }



        $data=array();
        foreach ($list as $item) {
            $actual=(($item->at_total<0)?'(':'').number_format(abs($item->at_total),2,'.','').(($item->at_total<0)?')':'');
            $budget=(($item->bg_total+$item->bg_total_job<0)?'(':'').number_format(abs($item->bg_total+$item->bg_total_job),2,'.','').(($item->bg_total+$item->bg_total_job<0)?')':'');
            $diff=(($item->at_total-$item->bg_total+$item->bg_total_job<0)?'(':'').number_format(abs($item->at_total-($item->bg_total+$item->bg_total_job)),2).(($item->at_total-$item->bg_total+$item->bg_total_job<0)?')':'');

            if(is_numeric($item->bg_total))
              $variance=number_format((($item->at_total-$item->bg_total)*100)/$item->bg_total,2).'%';
            else
              $variance="";


            $data[]=array(
                'JobNumber'       =>  $item->job_number, 
                'JobName'         =>  $item->job_description, 
                'Actual'          =>  $actual,
                'Budget'          =>  $budget,
                'Variance'        =>  $diff,
                );
        }

        Excel::create('Budget-Actual-Target-'.strtotime("now"), function($excel) use($data){

            $excel->sheet('Excel sheet', function($sheet) use($data){
                 $sheet->fromArray($data);
            });

        })->download('xls');

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function changeMonth(Request $request, $type, $month)
    {
      if($type==1){
        $request->session()->put('rpt_ini_date', $month);  
      }else{
        $request->session()->put('rpt_end_date', $month);
      }
      
      return Response::json(
              array('status'        => $month)
      );
    }


}
