<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;


use App\Job;

use DB;
use Auth;
use Response;
use Excel;
use File;
use DateTime;
use Carbon\Carbon;

 use JonnyW\PhantomJs\Client;


class ReportController extends Controller
{
    protected $pipeline_valid=['default', 'a965b514-ee76-4f9c-ac23-e7cff4b38c32' , '551457d6-d408-4500-ad2c-bec6c3a3f3ea', '6b3c3c1c-05a8-4770-91df-24a59b675723'];
    
    public function getPhantom()
    {
      $client = Client::getInstance();
        $client->getEngine()->setPath(base_path() . '/phantomjs');
        /**
         * @see JonnyW\PhantomJs\Http\Request
         **/
        $request = $client->getMessageFactory()->createRequest('http://trefra.group', 'GET');
        /**
         * @see JonnyW\PhantomJs\Http\Response
         **/
        $response = $client->getMessageFactory()->createResponse();
        // Send the request
        $client->send($request, $response);
        if ($response->getStatus() === 200) {
            // Dump the requested page content
            echo $response->getContent();
        }
      
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * integer $type
     */
    
    public function getIndex(Request $request, $type=1)
    {
        $bg_ini_range = $request->session()->get('rpt_ini_date', function() {
            return '1';
        });

        $bg_end_range = $request->session()->get('rpt_end_date', function() {
            return '12';
        });

        //Year
        $bg_year = $request->session()->get('rpt_year', function() {
            return '0';
        });

        //Calculate values
        $bg_ini_range=$bg_ini_range+$bg_year;
        $bg_end_range=$bg_end_range+$bg_year;


        if($type==1){
          //REPORT 1
          $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, job.country, job.division, job.mayor_account, job.manager, accounting_gl.level, SUM(accounting_gl.total) as "total"'))  
                  ->leftJoin('accounting_gl', 'job.job_number' , '=' , 'accounting_gl.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('accounting_gl.period', '>=', $bg_ini_range)
                  ->where('accounting_gl.period', '<=', $bg_end_range)
                  ->groupBy('job.job_number')
                  ->groupBy('accounting_gl.level')
                  ->orderBy('job.job_number', 'asc')
                  ->get();

          $data_county=array();

          //Country
          $county_list=$this->listCounty();

          foreach ($county_list as $key => $value) {
            $data_county[$key]['revenue']=0;
            $data_county[$key]['cost']=0;
         }     

         foreach ($list as $item) {
            if(isset($county_list[$item->country])){
              if($item->level==1){
                $data_county[$item->country]['revenue']+=$item->total;
              }else{
                $data_county[$item->country]['cost']+=$item->total;
              }
            }
         } 

          $data_industry=array();

          //Industry 
          $industry_list=$this->listIndustry(); 

          foreach ($industry_list as $key => $value) {
            $data_industry[$key]['revenue']=0;
            $data_industry[$key]['cost']=0;
         }     

         foreach ($list as $item) {
            if(isset($industry_list[$item->division])){
              if($item->level==1){
                $data_industry[$item->division]['revenue']+=$item->total;
              }else{
                $data_industry[$item->division]['cost']+=$item->total;
              }
            }
         }         

          $data_mayor=array();

          //Mayor
          $mayor_list=$this->listMayorAccount(); 

          foreach ($mayor_list as $key => $value) {
              $data_mayor[$key]['revenue']=0;
              $data_mayor[$key]['cost']=0;
           }     

           foreach ($list as $item) {
              if(isset($mayor_list[$item->mayor_account])){
                if($item->level==1){
                  $data_mayor[$item->mayor_account]['revenue']+=$item->total;
                }else{
                  $data_mayor[$item->mayor_account]['cost']+=$item->total;
                }
              }
           }   

          $data_manager=array();


          //Manager
          $manager_list=$this->listManager(); 

          foreach ($manager_list as $key => $value) {
            $data_manager[$key]['revenue']=0;
            $data_manager[$key]['cost']=0;
          }     

         foreach ($list as $item) {
            if(isset($manager_list[$item->manager])){
              if($item->level==1){
                $data_manager[$item->manager]['revenue']+=$item->total;
              }else{
                $data_manager[$item->manager]['cost']+=$item->total;
              }
            }
         }   

          return view('report.home', ['month_ini'     =>  ($bg_ini_range-$bg_year), 
                                      'month_end'     =>  ($bg_end_range-$bg_year), 
                                      'year_filter'   =>  $bg_year,
                                      'type'          =>  $type,
                                      'county_list'   =>  $county_list,
                                      'industry_list' =>  $industry_list,
                                      'mayor_list'    =>  $mayor_list,
                                      'manager_list'  =>  $manager_list,
                                      'data_county'   =>  $data_county,
                                      'data_industry' =>  $data_industry,
                                      'data_mayor'    =>  $data_mayor,
                                      'data_manager'  =>  $data_manager,
                                      ]);

        }elseif ($type==2) {
          //REPORT 2
          $month=($bg_end_range-$bg_ini_range)+1;

          $list_budget=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                    (SELECT -SUM(budget_data.total) FROM budget_data WHERE budget_data.job_number=job.job_number AND budget_data.period >='.$bg_ini_range.' AND budget_data.period <='.$bg_end_range.') as "total",
                                    (SELECT -(budget_gl.period1*'.$month.') FROM budget_gl WHERE budget_gl.job_number=job.job_number AND budget_gl.period ='.$bg_year.') as "total_job"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

          $list_actual=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                      (SELECT -SUM(actual_gl.actual_total) FROM actual_gl WHERE actual_gl.job_number=job.job_number AND actual_gl.period >='.$bg_ini_range.' AND actual_gl.period <='.$bg_end_range.') as "total"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

          //Country
          $data_county_budget=array();
          $data_county_actual=array();
          $county_list=$this->listCounty();

          foreach ($county_list as $key => $value) {
            $data_county_budget[$key]=0;
            $data_county_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($county_list[$item->country])){
              $data_county_budget[$item->country]+=$item->total_job+$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($county_list[$item->country])){
              $data_county_actual[$item->country]+=$item->total;
            }
          }

          //Industry 
          $data_industry_budget=array();
          $data_industry_actual=array();
          $industry_list=$this->listIndustry(); 

          foreach ($industry_list as $key => $value) {
            $data_industry_budget[$key]=0;
            $data_industry_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($industry_list[$item->division])){
              $data_industry_budget[$item->division]+=$item->total_job+$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($industry_list[$item->division])){
              $data_industry_actual[$item->division]+=$item->total;
            }
          }

          //Mayor
          $data_mayor_budget=array();
          $data_mayor_actual=array();
          $mayor_list=$this->listMayorAccount(); 

          foreach ($mayor_list as $key => $value) {
            $data_mayor_budget[$key]=0;
            $data_mayor_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($mayor_list[$item->mayor_account])){
              $data_mayor_budget[$item->mayor_account]+=$item->total_job+$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($mayor_list[$item->mayor_account])){
              $data_mayor_actual[$item->mayor_account]+=$item->total;
            }
          }

          //Manager
          $data_manager_budget=array();
          $data_manager_actual=array();
          $manager_list=$this->listManager(); 

          foreach ($manager_list as $key => $value) {
            $data_manager_budget[$key]=0;
            $data_manager_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($manager_list[$item->manager])){
              $data_manager_budget[$item->manager]+=$item->total_job+$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($manager_list[$item->manager])){
              $data_manager_actual[$item->manager]+=$item->total;
            }
          }

          



          return view('report.home_2', ['month_ini'             =>  ($bg_ini_range-$bg_year), 
                                        'month_end'             =>  ($bg_end_range-$bg_year), 
                                        'year_filter'           =>  $bg_year,
                                        'type'                  =>  $type,
                                        'county_list'           =>  $county_list,
                                        'industry_list'         =>  $industry_list,
                                        'mayor_list'            =>  $mayor_list,
                                        'manager_list'          =>  $manager_list,
                                        'data_county_budget'    =>  $data_county_budget,
                                        'data_county_actual'    =>  $data_county_actual,
                                        'data_industry_budget'  =>  $data_industry_budget,
                                        'data_industry_actual'  =>  $data_industry_actual,
                                        'data_mayor_budget'     =>  $data_mayor_budget,
                                        'data_mayor_actual'     =>  $data_mayor_actual,
                                        'data_manager_budget'   =>  $data_manager_budget,
                                        'data_manager_actual'   =>  $data_manager_actual,
                                        'list_actual'           =>  $list_actual
                                        ] );
        }elseif ($type==3) {
          //REPORT 3

          $dateStart = Carbon::now();
          $iniDateStart=$dateStart->copy()->subWeek(8);
          $endDateStart=$dateStart->copy()->subDay(1);

          $tk_ini_range = $request->session()->get('tk_ini_date', function() {
            $dateRange = Carbon::now();
            $iniDate=$dateRange->copy()->subWeek(8);
            return $iniDate->format('Y-m-d');
          });

          $tk_end_range = $request->session()->get('tk_end_date', function() {
              $dateRange = Carbon::now();
              $endDate=$dateRange->copy()->subDay(1);
              return $endDate->format('Y-m-d');
          });

          $list_budget=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                    (SELECT SUM(labor_tax.budget_hour) FROM labor_tax WHERE labor_tax.job_number=job.job_number AND labor_tax.date >="'.$tk_ini_range.'" AND labor_tax.date <="'.$tk_end_range.'") as "total"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

          $list_actual=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                      (SELECT SUM(timekeeping.hours) FROM timekeeping WHERE timekeeping.job_number=job.job_number AND timekeeping.work_date >="'.$tk_ini_range.'" AND timekeeping.work_date <="'.$tk_end_range.'") as "total"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

          //Country
          $data_county_budget=array();
          $data_county_actual=array();
          $county_list=$this->listCounty();

          foreach ($county_list as $key => $value) {
            $data_county_budget[$key]=0;
            $data_county_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($county_list[$item->country])){
              $data_county_budget[$item->country]+=$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($county_list[$item->country])){
              $data_county_actual[$item->country]+=$item->total;
            }
          }

          //Industry 
          $data_industry_budget=array();
          $data_industry_actual=array();
          $industry_list=$this->listIndustry(); 

          foreach ($industry_list as $key => $value) {
            $data_industry_budget[$key]=0;
            $data_industry_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($industry_list[$item->division])){
              $data_industry_budget[$item->division]+=$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($industry_list[$item->division])){
              $data_industry_actual[$item->division]+=$item->total;
            }
          }

          //Mayor
          $data_mayor_budget=array();
          $data_mayor_actual=array();
          $mayor_list=$this->listMayorAccount(); 

          foreach ($mayor_list as $key => $value) {
            $data_mayor_budget[$key]=0;
            $data_mayor_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($mayor_list[$item->mayor_account])){
              $data_mayor_budget[$item->mayor_account]+=$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($mayor_list[$item->mayor_account])){
              $data_mayor_actual[$item->mayor_account]+=$item->total;
            }
          }

          //Manager
          $data_manager_budget=array();
          $data_manager_actual=array();
          $manager_list=$this->listManager(); 

          foreach ($manager_list as $key => $value) {
            $data_manager_budget[$key]=0;
            $data_manager_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($manager_list[$item->manager])){
              $data_manager_budget[$item->manager]+=$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($manager_list[$item->manager])){
              $data_manager_actual[$item->manager]+=$item->total;
            }
          }



          return view('report.home_3', ['date_ini'              =>  $tk_ini_range, 
                                        'date_end'              =>  $tk_end_range, 
                                        'date_ini_data'         =>  $iniDateStart->format('Y-m-d'), 
                                        'date_end_data'         =>  $endDateStart->format('Y-m-d'),
                                        'type'                  =>  $type,
                                        'county_list'           =>  $county_list,
                                        'industry_list'         =>  $industry_list,
                                        'mayor_list'            =>  $mayor_list,
                                        'manager_list'          =>  $manager_list,
                                        'data_county_budget'    =>  $data_county_budget,
                                        'data_county_actual'    =>  $data_county_actual,
                                        'data_industry_budget'  =>  $data_industry_budget,
                                        'data_industry_actual'  =>  $data_industry_actual,
                                        'data_mayor_budget'     =>  $data_mayor_budget,
                                        'data_mayor_actual'     =>  $data_mayor_actual,
                                        'data_manager_budget'   =>  $data_manager_budget,
                                        'data_manager_actual'   =>  $data_manager_actual,
                                        'list_actual'           =>  $list_actual
                                        ] );
        }elseif ($type==4) {
          //REPORT 4 - Sales

          $sales_ini_range = $request->session()->get('sales_ini_date', function() {
            return date('Y-01-01');
          });

          $sales_end_range = $request->session()->get('sales_end_date', function() {
              return date('Y-12-31');
          });


          //Manager
          $data_manager=array();
          $data_manager_won=array();
          $data_manager_forecast=array();
          
          $manager_list=$this->accountManager(); 

          foreach ($manager_list as $key => $value) {
            $data_manager[$value]=0;
            $data_manager_won[$value]=0;
            $data_manager_forecast[$value]=0;
          }

          $list_manager=DB::table('deal')
                    ->select(DB::raw('deal.account_manager, SUM(amount) as "total"'))  
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.account_manager')
                    ->get();

          $list_manager_won=DB::table('deal')
                    ->select(DB::raw('deal.account_manager, SUM(amount) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->where('deal_stage.close_won', '=', 1)
                    ->groupBy('deal.account_manager')
                    ->get();

          $list_manager_forecast=DB::table('deal')
                    ->select(DB::raw('deal.account_manager, SUM(amount*deal_stage.probability) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.account_manager')
                    ->get();

          foreach ($list_manager as $value) {
            $manager=$value->account_manager;
            if($manager=="")
              $manager="None";

            $data_manager[$manager]=$value->total;  
          }

          foreach ($list_manager_won as $value) {
            $manager=$value->account_manager;
            if($manager=="")
              $manager="None";

            $data_manager_won[$manager]=$value->total;  
          }

          foreach ($list_manager_forecast as $value) {
            $manager=$value->account_manager;
            if($manager=="")
              $manager="None";

            $data_manager_forecast[$manager]=$value->total;  
          }

          //Industry
          $data_industry=array();
          $data_industry_won=array();
          $data_industry_forecast=array();
          
          $industry_list=$this->accountIndustry(); 

          foreach ($industry_list as $key => $value) {
            $data_industry[$value]=0;
            $data_industry_won[$value]=0;
            $data_industry_forecast[$value]=0;
          }

          $list_industry=DB::table('deal')
                    ->select(DB::raw('deal.deal_vertical, SUM(amount) as "total"'))  
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.deal_vertical')
                    ->get();

          $list_industry_won=DB::table('deal')
                    ->select(DB::raw('deal.deal_vertical, SUM(amount) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->where('deal_stage.close_won', '=', 1)
                    ->groupBy('deal.deal_vertical')
                    ->get();

          $list_industry_forecast=DB::table('deal')
                    ->select(DB::raw('deal.deal_vertical, SUM(amount*deal_stage.probability) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.deal_vertical')
                    ->get();

          foreach ($list_industry as $value) {
            $industry=$value->deal_vertical;
            if($industry=="")
              $industry="None";

            $data_industry[$industry]=$value->total;  
          }

          foreach ($list_industry_won as $value) {
            $industry=$value->deal_vertical;
            if($industry=="")
              $industry="None";

            $data_industry_won[$industry]=$value->total;  
          }

          foreach ($list_industry_forecast as $value) {
            $industry=$value->deal_vertical;
            if($industry=="")
              $industry="None";

            $data_industry_forecast[$industry]=$value->total;  
          }


          //Pipeline
          $data_pipeline=array();
          $data_pipeline_won=array();
          $data_pipeline_forecast=array();
          
          $pipeline_list=$this->accountPipeline(); 

          foreach ($pipeline_list as $key => $value) {
            $data_pipeline[$key]=0;
            $data_pipeline_won[$key]=0;
            $data_pipeline_forecast[$key]=0;
          }

          $list_pipeline=DB::table('deal')
                    ->select(DB::raw('deal.pipeline_id, SUM(amount) as "total"'))  
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.pipeline_id')
                    ->get();

          $list_pipeline_won=DB::table('deal')
                    ->select(DB::raw('deal.pipeline_id, SUM(amount) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->where('deal_stage.close_won', '=', 1)
                    ->groupBy('deal.pipeline_id')
                    ->get();

          $list_pipeline_forecast=DB::table('deal')
                    ->select(DB::raw('deal.pipeline_id, SUM(amount*deal_stage.probability) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.pipeline_id')
                    ->get();

          foreach ($list_pipeline as $value) {
            $pipeline_id=$value->pipeline_id;
            if($pipeline_id=="")
              $pipeline_id="None";

            $data_pipeline[$pipeline_id]=$value->total;  
          }

          foreach ($list_pipeline_won as $value) {
            $pipeline_id=$value->pipeline_id;
            if($pipeline_id=="")
              $pipeline_id="None";

            $data_pipeline_won[$pipeline_id]=$value->total;  
          }

          foreach ($list_pipeline_forecast as $value) {
            $pipeline_id=$value->pipeline_id;
            if($pipeline_id=="")
              $pipeline_id="None";

            $data_pipeline_forecast[$pipeline_id]=$value->total;  
          }

          //Hubspot Owner
          $data_hubspotowner=array();
          $data_hubspotowner_won=array();
          $data_hubspotowner_forecast=array();
          
          $hubspotowner_list=$this->accountHubspotOwner(); 

          foreach ($hubspotowner_list as $key => $value) {
            $data_hubspotowner[$key]=0;
            $data_hubspotowner_won[$key]=0;
            $data_hubspotowner_forecast[$key]=0;
          }

          $list_hubspotowner=DB::table('deal')
                    ->select(DB::raw('deal.hubspot_owner_id, SUM(amount) as "total"'))  
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.hubspot_owner_id')
                    ->get();

          $list_hubspotowner_won=DB::table('deal')
                    ->select(DB::raw('deal.hubspot_owner_id, SUM(amount) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->where('deal_stage.close_won', '=', 1)
                    ->groupBy('deal.hubspot_owner_id')
                    ->get();

          $list_hubspotowner_forecast=DB::table('deal')
                    ->select(DB::raw('deal.hubspot_owner_id, SUM(amount*deal_stage.probability) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.hubspot_owner_id')
                    ->get();

          foreach ($list_hubspotowner as $value) {
            $hubspot_owner_id=$value->hubspot_owner_id;
            if($hubspot_owner_id=="")
              $hubspot_owner_id="None";

            $data_hubspotowner[$hubspot_owner_id]=$value->total;  
          }

          foreach ($list_hubspotowner_won as $value) {
            $hubspot_owner_id=$value->hubspot_owner_id;
            if($hubspot_owner_id=="")
              $hubspot_owner_id="None";

            $data_hubspotowner_won[$hubspot_owner_id]=$value->total;  
          }

          foreach ($list_hubspotowner_forecast as $value) {
            $hubspot_owner_id=$value->hubspot_owner_id;
            if($hubspot_owner_id=="")
              $hubspot_owner_id="None";

            $data_hubspotowner_forecast[$hubspot_owner_id]=$value->total;  
          }

      

          return view('report.home_4', ['date_ini'              =>  $sales_ini_range, 
                                        'date_end'              =>  $sales_end_range, 
                                        'type'                  =>  $type,
                                        'manager_list'          =>  $manager_list,
                                        'data_manager'          =>  $data_manager,
                                        'data_manager_won'      =>  $data_manager_won,
                                        'data_manager_forecast' =>  $data_manager_forecast,
                                        'industry_list'         =>  $industry_list,
                                        'data_industry'         =>  $data_industry,
                                        'data_industry_won'     =>  $data_industry_won,
                                        'data_industry_forecast'=>  $data_industry_forecast,
                                        'pipeline_list'         =>  $pipeline_list,
                                        'data_pipeline'         =>  $data_pipeline,
                                        'data_pipeline_won'     =>  $data_pipeline_won,
                                        'data_pipeline_forecast'=>  $data_pipeline_forecast,
                                        'hubspotowner_list'     =>  $hubspotowner_list,
                                        'data_hubspotowner'         =>  $data_hubspotowner,
                                        'data_hubspotowner_won'     =>  $data_hubspotowner_won,
                                        'data_hubspotowner_forecast'=>  $data_hubspotowner_forecast,
                                        ] );
        }elseif($type==5){
          //REPORT 5 - LER
          $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, job.country, job.division, job.mayor_account, job.manager, 
                                    accounting_gl.level, SUM(accounting_gl.total) as "total"
                                    '))  
                  ->leftJoin('accounting_gl', 'job.job_number' , '=' , 'accounting_gl.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('accounting_gl.period', '>=', $bg_ini_range)
                  ->where('accounting_gl.period', '<=', $bg_end_range)
                  ->groupBy('job.job_number')
                  ->groupBy('accounting_gl.level')
                  ->orderBy('job.job_number', 'asc')
                  ->get();


          $list_hours = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, job.country, job.division, job.mayor_account, job.manager, 
                                    job.job_description, SUM(timekeeping_data.hours) as "hours"
                                    '))  
                  ->leftJoin('timekeeping_data', 'job.job_number' , '=' , 'timekeeping_data.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('timekeeping_data.period', '>=', $bg_ini_range)
                  ->where('timekeeping_data.period', '<=', $bg_end_range)
                  ->groupBy('job.job_number')
                  ->orderBy('job.job_number', 'asc')
                  ->get();


          $data_county=array();
          $data_county_hours=array();
          $data_county_ler=array();

          //Country
          $county_list=$this->listCounty();

          foreach ($county_list as $key => $value) {
            $data_county[$key]['revenue']=0;
            $data_county[$key]['cost']=0;
            $data_county_hours[$key]=0;
            $data_county_ler[$key]=0;
         }     

         foreach ($list as $item) {
            if(isset($county_list[$item->country])){
              if($item->level==1){
                $data_county[$item->country]['revenue']+=$item->total;
              }else{
                $data_county[$item->country]['cost']+=$item->total;
              }
            }
         }

         foreach ($list_hours as $item) {
            if(isset($county_list[$item->country])){
              $data_county_hours[$item->country]+=$item->hours;
            }
         } 

         //LER Calculation
         foreach ($county_list as $key => $value) {
            $gross_profit=abs($data_county[$key]['revenue'])-$data_county[$key]['cost'];
            $hours=(($data_county_hours[$key]!=0)?$data_county_hours[$key]:1);
            $data_county_ler[$key]=$gross_profit/$hours;
         }


          $data_industry=array();
          $data_industry_hours=array();
          $data_industry_ler=array();

          //Industry 
          $industry_list=$this->listIndustry(); 

          foreach ($industry_list as $key => $value) {
            $data_industry[$key]['revenue']=0;
            $data_industry[$key]['cost']=0;
            $data_industry_hours[$key]=0;
         }     

         foreach ($list as $item) {
            if(isset($industry_list[$item->division])){
              if($item->level==1){
                $data_industry[$item->division]['revenue']+=$item->total;
              }else{
                $data_industry[$item->division]['cost']+=$item->total;
              }
            }
         }

         foreach ($list_hours as $item) {
            if(isset($industry_list[$item->division])){
              $data_industry_hours[$item->division]+=$item->hours;
            }
         }     

         //LER Calculation
         foreach ($industry_list as $key => $value) {
            $gross_profit=abs($data_industry[$key]['revenue'])-$data_industry[$key]['cost'];
            $hours=(($data_industry_hours[$key]!=0)?$data_industry_hours[$key]:1);
            $data_industry_ler[$key]=$gross_profit/$hours;
         }    

          $data_mayor=array();
          $data_mayor_hours=array();
          $data_mayor_ler=array();

          //Mayor
          $mayor_list=$this->listMayorAccount(); 

          foreach ($mayor_list as $key => $value) {
              $data_mayor[$key]['revenue']=0;
              $data_mayor[$key]['cost']=0;
              $data_mayor_hours[$key]=0;
           }     

           foreach ($list as $item) {
              if(isset($mayor_list[$item->mayor_account])){
                if($item->level==1){
                  $data_mayor[$item->mayor_account]['revenue']+=$item->total;
                }else{
                  $data_mayor[$item->mayor_account]['cost']+=$item->total;
                }
              }
           }

           foreach ($list_hours as $item) {
              if(isset($mayor_list[$item->mayor_account])){
                $data_mayor_hours[$item->mayor_account]+=$item->hours;
              }
           }   

           //LER Calculation
         foreach ($mayor_list as $key => $value) {
            $gross_profit=abs($data_mayor[$key]['revenue'])-$data_mayor[$key]['cost'];
            $hours=(($data_mayor_hours[$key]!=0)?$data_mayor_hours[$key]:1);
            $data_mayor_ler[$key]=$gross_profit/$hours;
         } 

          $data_manager=array();
          $data_manager_hours=array();
          $data_manager_ler=array();

          //Manager
          $manager_list=$this->listManager(); 

          foreach ($manager_list as $key => $value) {
            $data_manager[$key]['revenue']=0;
            $data_manager[$key]['cost']=0;
            $data_manager_hours[$key]=0;
          }     

         foreach ($list as $item) {
            if(isset($manager_list[$item->manager])){
              if($item->level==1){
                $data_manager[$item->manager]['revenue']+=$item->total;
              }else{
                $data_manager[$item->manager]['cost']+=$item->total;
              }
            }
         }

         foreach ($list_hours as $item) {
            if(isset($manager_list[$item->manager])){
              $data_manager_hours[$item->manager]+=$item->hours;
            }
         }

          //LER Calculation
         foreach ($manager_list as $key => $value) {
            $gross_profit=abs($data_manager[$key]['revenue'])-$data_manager[$key]['cost'];
            $hours=(($data_manager_hours[$key]!=0)?$data_manager_hours[$key]:1);
            $data_manager_ler[$key]=$gross_profit/$hours;
         }   

          return view('report.home_5', ['month_ini'     =>  ($bg_ini_range-$bg_year), 
                                      'month_end'     =>  ($bg_end_range-$bg_year), 
                                      'year_filter'   =>  $bg_year,
                                      'type'          =>  $type,
                                      'county_list'   =>  $county_list,
                                      'industry_list' =>  $industry_list,
                                      'mayor_list'    =>  $mayor_list,
                                      'manager_list'  =>  $manager_list,
                                      'data_county'   =>  $data_county,
                                      'data_industry' =>  $data_industry,
                                      'data_mayor'    =>  $data_mayor,
                                      'data_manager'  =>  $data_manager,
                                      'data_county_ler'   =>  $data_county_ler,
                                      'data_industry_ler' =>  $data_industry_ler,
                                      'data_mayor_ler'    =>  $data_mayor_ler,
                                      'data_manager_ler'  =>  $data_manager_ler,
                                      ]);

        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * integer $type
     */
    
    public function getReportPdf(Request $request, $type=1)
    {
        $bg_ini_range = $request->session()->get('rpt_ini_date', function() {
            return '1';
        });

        $bg_end_range = $request->session()->get('rpt_end_date', function() {
            return '12';
        });

        //Year
        $bg_year = $request->session()->get('rpt_year', function() {
            return '0';
        });

        //Calculate values
        $bg_ini_range=$bg_ini_range+$bg_year;
        $bg_end_range=$bg_end_range+$bg_year;


        if($type==1){
          //REPORT 1
          $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, job.country, job.division, job.mayor_account, job.manager, accounting_gl.level, SUM(accounting_gl.total) as "total"'))  
                  ->leftJoin('accounting_gl', 'job.job_number' , '=' , 'accounting_gl.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('accounting_gl.period', '>=', $bg_ini_range)
                  ->where('accounting_gl.period', '<=', $bg_end_range)
                  ->groupBy('job.job_number')
                  ->groupBy('accounting_gl.level')
                  ->orderBy('job.job_number', 'asc')
                  ->get();

          $data_county=array();

          //Country
          $county_list=$this->listCounty();

          foreach ($county_list as $key => $value) {
            $data_county[$key]['revenue']=0;
            $data_county[$key]['cost']=0;
         }     

         foreach ($list as $item) {
            if(isset($county_list[$item->country])){
              if($item->level==1){
                $data_county[$item->country]['revenue']+=$item->total;
              }else{
                $data_county[$item->country]['cost']+=$item->total;
              }
            }
         } 

          $data_industry=array();

          //Industry 
          $industry_list=$this->listIndustry(); 

          foreach ($industry_list as $key => $value) {
            $data_industry[$key]['revenue']=0;
            $data_industry[$key]['cost']=0;
         }     

         foreach ($list as $item) {
            if(isset($industry_list[$item->division])){
              if($item->level==1){
                $data_industry[$item->division]['revenue']+=$item->total;
              }else{
                $data_industry[$item->division]['cost']+=$item->total;
              }
            }
         }         

          $data_mayor=array();

          //Mayor
          $mayor_list=$this->listMayorAccount(); 

          foreach ($mayor_list as $key => $value) {
              $data_mayor[$key]['revenue']=0;
              $data_mayor[$key]['cost']=0;
           }     

           foreach ($list as $item) {
              if(isset($mayor_list[$item->mayor_account])){
                if($item->level==1){
                  $data_mayor[$item->mayor_account]['revenue']+=$item->total;
                }else{
                  $data_mayor[$item->mayor_account]['cost']+=$item->total;
                }
              }
           }   

          $data_manager=array();


          //Manager
          $manager_list=$this->listManager(); 

          foreach ($manager_list as $key => $value) {
            $data_manager[$key]['revenue']=0;
            $data_manager[$key]['cost']=0;
          }     

         foreach ($list as $item) {
            if(isset($manager_list[$item->manager])){
              if($item->level==1){
                $data_manager[$item->manager]['revenue']+=$item->total;
              }else{
                $data_manager[$item->manager]['cost']+=$item->total;
              }
            }
         }   

          return view('report.home_pdf', ['month_ini'     =>  ($bg_ini_range-$bg_year), 
                                      'month_end'     =>  ($bg_end_range-$bg_year), 
                                      'year_filter'   =>  $bg_year,
                                      'type'          =>  $type,
                                      'county_list'   =>  $county_list,
                                      'industry_list' =>  $industry_list,
                                      'mayor_list'    =>  $mayor_list,
                                      'manager_list'  =>  $manager_list,
                                      'data_county'   =>  $data_county,
                                      'data_industry' =>  $data_industry,
                                      'data_mayor'    =>  $data_mayor,
                                      'data_manager'  =>  $data_manager,
                                      ])->render();

          /*$pdf = \App::make('dompdf.wrapper');
          $pdf->loadHTML($view);
          return $pdf->stream('report-profitability.pdf');*/

        }elseif ($type==2) {
          //REPORT 2
          $month=($bg_end_range-$bg_ini_range)+1;

          $list_budget=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                    (SELECT -SUM(budget_data.total) FROM budget_data WHERE budget_data.job_number=job.job_number AND budget_data.period >='.$bg_ini_range.' AND budget_data.period <='.$bg_end_range.') as "total",
                                    (SELECT -(budget_gl.period1*'.$month.') FROM budget_gl WHERE budget_gl.job_number=job.job_number AND budget_gl.period ='.$bg_year.') as "total_job"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

          $list_actual=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                      (SELECT -SUM(actual_gl.actual_total) FROM actual_gl WHERE actual_gl.job_number=job.job_number AND actual_gl.period >='.$bg_ini_range.' AND actual_gl.period <='.$bg_end_range.') as "total"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

          //Country
          $data_county_budget=array();
          $data_county_actual=array();
          $county_list=$this->listCounty();

          foreach ($county_list as $key => $value) {
            $data_county_budget[$key]=0;
            $data_county_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($county_list[$item->country])){
              $data_county_budget[$item->country]+=$item->total_job+$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($county_list[$item->country])){
              $data_county_actual[$item->country]+=$item->total;
            }
          }

          //Industry 
          $data_industry_budget=array();
          $data_industry_actual=array();
          $industry_list=$this->listIndustry(); 

          foreach ($industry_list as $key => $value) {
            $data_industry_budget[$key]=0;
            $data_industry_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($industry_list[$item->division])){
              $data_industry_budget[$item->division]+=$item->total_job+$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($industry_list[$item->division])){
              $data_industry_actual[$item->division]+=$item->total;
            }
          }

          //Mayor
          $data_mayor_budget=array();
          $data_mayor_actual=array();
          $mayor_list=$this->listMayorAccount(); 

          foreach ($mayor_list as $key => $value) {
            $data_mayor_budget[$key]=0;
            $data_mayor_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($mayor_list[$item->mayor_account])){
              $data_mayor_budget[$item->mayor_account]+=$item->total_job+$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($mayor_list[$item->mayor_account])){
              $data_mayor_actual[$item->mayor_account]+=$item->total;
            }
          }

          //Manager
          $data_manager_budget=array();
          $data_manager_actual=array();
          $manager_list=$this->listManager(); 

          foreach ($manager_list as $key => $value) {
            $data_manager_budget[$key]=0;
            $data_manager_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($manager_list[$item->manager])){
              $data_manager_budget[$item->manager]+=$item->total_job+$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($manager_list[$item->manager])){
              $data_manager_actual[$item->manager]+=$item->total;
            }
          }

          



          return view('report.home_2', ['month_ini'             =>  ($bg_ini_range-$bg_year), 
                                        'month_end'             =>  ($bg_end_range-$bg_year), 
                                        'year_filter'           =>  $bg_year,
                                        'type'                  =>  $type,
                                        'county_list'           =>  $county_list,
                                        'industry_list'         =>  $industry_list,
                                        'mayor_list'            =>  $mayor_list,
                                        'manager_list'          =>  $manager_list,
                                        'data_county_budget'    =>  $data_county_budget,
                                        'data_county_actual'    =>  $data_county_actual,
                                        'data_industry_budget'  =>  $data_industry_budget,
                                        'data_industry_actual'  =>  $data_industry_actual,
                                        'data_mayor_budget'     =>  $data_mayor_budget,
                                        'data_mayor_actual'     =>  $data_mayor_actual,
                                        'data_manager_budget'   =>  $data_manager_budget,
                                        'data_manager_actual'   =>  $data_manager_actual,
                                        'list_actual'           =>  $list_actual
                                        ] );
        }elseif ($type==3) {
          //REPORT 3

          $dateStart = Carbon::now();
          $iniDateStart=$dateStart->copy()->subWeek(8);
          $endDateStart=$dateStart->copy()->subDay(1);

          $tk_ini_range = $request->session()->get('tk_ini_date', function() {
            $dateRange = Carbon::now();
            $iniDate=$dateRange->copy()->subWeek(8);
            return $iniDate->format('Y-m-d');
          });

          $tk_end_range = $request->session()->get('tk_end_date', function() {
              $dateRange = Carbon::now();
              $endDate=$dateRange->copy()->subDay(1);
              return $endDate->format('Y-m-d');
          });

          $list_budget=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                    (SELECT SUM(labor_tax.budget_hour) FROM labor_tax WHERE labor_tax.job_number=job.job_number AND labor_tax.date >="'.$tk_ini_range.'" AND labor_tax.date <="'.$tk_end_range.'") as "total"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

          $list_actual=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                      (SELECT SUM(timekeeping.hours) FROM timekeeping WHERE timekeeping.job_number=job.job_number AND timekeeping.work_date >="'.$tk_ini_range.'" AND timekeeping.work_date <="'.$tk_end_range.'") as "total"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

          //Country
          $data_county_budget=array();
          $data_county_actual=array();
          $county_list=$this->listCounty();

          foreach ($county_list as $key => $value) {
            $data_county_budget[$key]=0;
            $data_county_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($county_list[$item->country])){
              $data_county_budget[$item->country]+=$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($county_list[$item->country])){
              $data_county_actual[$item->country]+=$item->total;
            }
          }

          //Industry 
          $data_industry_budget=array();
          $data_industry_actual=array();
          $industry_list=$this->listIndustry(); 

          foreach ($industry_list as $key => $value) {
            $data_industry_budget[$key]=0;
            $data_industry_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($industry_list[$item->division])){
              $data_industry_budget[$item->division]+=$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($industry_list[$item->division])){
              $data_industry_actual[$item->division]+=$item->total;
            }
          }

          //Mayor
          $data_mayor_budget=array();
          $data_mayor_actual=array();
          $mayor_list=$this->listMayorAccount(); 

          foreach ($mayor_list as $key => $value) {
            $data_mayor_budget[$key]=0;
            $data_mayor_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($mayor_list[$item->mayor_account])){
              $data_mayor_budget[$item->mayor_account]+=$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($mayor_list[$item->mayor_account])){
              $data_mayor_actual[$item->mayor_account]+=$item->total;
            }
          }

          //Manager
          $data_manager_budget=array();
          $data_manager_actual=array();
          $manager_list=$this->listManager(); 

          foreach ($manager_list as $key => $value) {
            $data_manager_budget[$key]=0;
            $data_manager_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($manager_list[$item->manager])){
              $data_manager_budget[$item->manager]+=$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($manager_list[$item->manager])){
              $data_manager_actual[$item->manager]+=$item->total;
            }
          }



          return view('report.home_3', ['date_ini'              =>  $tk_ini_range, 
                                        'date_end'              =>  $tk_end_range, 
                                        'date_ini_data'         =>  $iniDateStart->format('Y-m-d'), 
                                        'date_end_data'         =>  $endDateStart->format('Y-m-d'),
                                        'type'                  =>  $type,
                                        'county_list'           =>  $county_list,
                                        'industry_list'         =>  $industry_list,
                                        'mayor_list'            =>  $mayor_list,
                                        'manager_list'          =>  $manager_list,
                                        'data_county_budget'    =>  $data_county_budget,
                                        'data_county_actual'    =>  $data_county_actual,
                                        'data_industry_budget'  =>  $data_industry_budget,
                                        'data_industry_actual'  =>  $data_industry_actual,
                                        'data_mayor_budget'     =>  $data_mayor_budget,
                                        'data_mayor_actual'     =>  $data_mayor_actual,
                                        'data_manager_budget'   =>  $data_manager_budget,
                                        'data_manager_actual'   =>  $data_manager_actual,
                                        'list_actual'           =>  $list_actual
                                        ] );
        }elseif ($type==4) {
          //REPORT 4 - Sales

          $sales_ini_range = $request->session()->get('sales_ini_date', function() {
            return date('Y-01-01');
          });

          $sales_end_range = $request->session()->get('sales_end_date', function() {
              return date('Y-12-31');
          });


          //Manager
          $data_manager=array();
          $data_manager_won=array();
          $data_manager_forecast=array();
          
          $manager_list=$this->accountManager(); 

          foreach ($manager_list as $key => $value) {
            $data_manager[$value]=0;
            $data_manager_won[$value]=0;
            $data_manager_forecast[$value]=0;
          }

          $list_manager=DB::table('deal')
                    ->select(DB::raw('deal.account_manager, SUM(amount) as "total"'))  
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.account_manager')
                    ->get();

          $list_manager_won=DB::table('deal')
                    ->select(DB::raw('deal.account_manager, SUM(amount) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->where('deal_stage.close_won', '=', 1)
                    ->groupBy('deal.account_manager')
                    ->get();

          $list_manager_forecast=DB::table('deal')
                    ->select(DB::raw('deal.account_manager, SUM(amount*deal_stage.probability) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.account_manager')
                    ->get();

          foreach ($list_manager as $value) {
            $manager=$value->account_manager;
            if($manager=="")
              $manager="None";

            $data_manager[$manager]=$value->total;  
          }

          foreach ($list_manager_won as $value) {
            $manager=$value->account_manager;
            if($manager=="")
              $manager="None";

            $data_manager_won[$manager]=$value->total;  
          }

          foreach ($list_manager_forecast as $value) {
            $manager=$value->account_manager;
            if($manager=="")
              $manager="None";

            $data_manager_forecast[$manager]=$value->total;  
          }

          //Industry
          $data_industry=array();
          $data_industry_won=array();
          $data_industry_forecast=array();
          
          $industry_list=$this->accountIndustry(); 

          foreach ($industry_list as $key => $value) {
            $data_industry[$value]=0;
            $data_industry_won[$value]=0;
            $data_industry_forecast[$value]=0;
          }

          $list_industry=DB::table('deal')
                    ->select(DB::raw('deal.deal_vertical, SUM(amount) as "total"'))  
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.deal_vertical')
                    ->get();

          $list_industry_won=DB::table('deal')
                    ->select(DB::raw('deal.deal_vertical, SUM(amount) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->where('deal_stage.close_won', '=', 1)
                    ->groupBy('deal.deal_vertical')
                    ->get();

          $list_industry_forecast=DB::table('deal')
                    ->select(DB::raw('deal.deal_vertical, SUM(amount*deal_stage.probability) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.deal_vertical')
                    ->get();

          foreach ($list_industry as $value) {
            $industry=$value->deal_vertical;
            if($industry=="")
              $industry="None";

            $data_industry[$industry]=$value->total;  
          }

          foreach ($list_industry_won as $value) {
            $industry=$value->deal_vertical;
            if($industry=="")
              $industry="None";

            $data_industry_won[$industry]=$value->total;  
          }

          foreach ($list_industry_forecast as $value) {
            $industry=$value->deal_vertical;
            if($industry=="")
              $industry="None";

            $data_industry_forecast[$industry]=$value->total;  
          }


          //Pipeline
          $data_pipeline=array();
          $data_pipeline_won=array();
          $data_pipeline_forecast=array();
          
          $pipeline_list=$this->accountPipeline(); 

          foreach ($pipeline_list as $key => $value) {
            $data_pipeline[$key]=0;
            $data_pipeline_won[$key]=0;
            $data_pipeline_forecast[$key]=0;
          }

          $list_pipeline=DB::table('deal')
                    ->select(DB::raw('deal.pipeline_id, SUM(amount) as "total"'))  
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.pipeline_id')
                    ->get();

          $list_pipeline_won=DB::table('deal')
                    ->select(DB::raw('deal.pipeline_id, SUM(amount) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->where('deal_stage.close_won', '=', 1)
                    ->groupBy('deal.pipeline_id')
                    ->get();

          $list_pipeline_forecast=DB::table('deal')
                    ->select(DB::raw('deal.pipeline_id, SUM(amount*deal_stage.probability) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.pipeline_id')
                    ->get();

          foreach ($list_pipeline as $value) {
            $pipeline_id=$value->pipeline_id;
            if($pipeline_id=="")
              $pipeline_id="None";

            $data_pipeline[$pipeline_id]=$value->total;  
          }

          foreach ($list_pipeline_won as $value) {
            $pipeline_id=$value->pipeline_id;
            if($pipeline_id=="")
              $pipeline_id="None";

            $data_pipeline_won[$pipeline_id]=$value->total;  
          }

          foreach ($list_pipeline_forecast as $value) {
            $pipeline_id=$value->pipeline_id;
            if($pipeline_id=="")
              $pipeline_id="None";

            $data_pipeline_forecast[$pipeline_id]=$value->total;  
          }

          //Hubspot Owner
          $data_hubspotowner=array();
          $data_hubspotowner_won=array();
          $data_hubspotowner_forecast=array();
          
          $hubspotowner_list=$this->accountHubspotOwner(); 

          foreach ($hubspotowner_list as $key => $value) {
            $data_hubspotowner[$key]=0;
            $data_hubspotowner_won[$key]=0;
            $data_hubspotowner_forecast[$key]=0;
          }

          $list_hubspotowner=DB::table('deal')
                    ->select(DB::raw('deal.hubspot_owner_id, SUM(amount) as "total"'))  
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.hubspot_owner_id')
                    ->get();

          $list_hubspotowner_won=DB::table('deal')
                    ->select(DB::raw('deal.hubspot_owner_id, SUM(amount) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->where('deal_stage.close_won', '=', 1)
                    ->groupBy('deal.hubspot_owner_id')
                    ->get();

          $list_hubspotowner_forecast=DB::table('deal')
                    ->select(DB::raw('deal.hubspot_owner_id, SUM(amount*deal_stage.probability) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.hubspot_owner_id')
                    ->get();

          foreach ($list_hubspotowner as $value) {
            $hubspot_owner_id=$value->hubspot_owner_id;
            if($hubspot_owner_id=="")
              $hubspot_owner_id="None";

            $data_hubspotowner[$hubspot_owner_id]=$value->total;  
          }

          foreach ($list_hubspotowner_won as $value) {
            $hubspot_owner_id=$value->hubspot_owner_id;
            if($hubspot_owner_id=="")
              $hubspot_owner_id="None";

            $data_hubspotowner_won[$hubspot_owner_id]=$value->total;  
          }

          foreach ($list_hubspotowner_forecast as $value) {
            $hubspot_owner_id=$value->hubspot_owner_id;
            if($hubspot_owner_id=="")
              $hubspot_owner_id="None";

            $data_hubspotowner_forecast[$hubspot_owner_id]=$value->total;  
          }

      

          return view('report.home_4', ['date_ini'              =>  $sales_ini_range, 
                                        'date_end'              =>  $sales_end_range, 
                                        'type'                  =>  $type,
                                        'manager_list'          =>  $manager_list,
                                        'data_manager'          =>  $data_manager,
                                        'data_manager_won'      =>  $data_manager_won,
                                        'data_manager_forecast' =>  $data_manager_forecast,
                                        'industry_list'         =>  $industry_list,
                                        'data_industry'         =>  $data_industry,
                                        'data_industry_won'     =>  $data_industry_won,
                                        'data_industry_forecast'=>  $data_industry_forecast,
                                        'pipeline_list'         =>  $pipeline_list,
                                        'data_pipeline'         =>  $data_pipeline,
                                        'data_pipeline_won'     =>  $data_pipeline_won,
                                        'data_pipeline_forecast'=>  $data_pipeline_forecast,
                                        'hubspotowner_list'     =>  $hubspotowner_list,
                                        'data_hubspotowner'         =>  $data_hubspotowner,
                                        'data_hubspotowner_won'     =>  $data_hubspotowner_won,
                                        'data_hubspotowner_forecast'=>  $data_hubspotowner_forecast,
                                        ] );
        }elseif($type==5){
          //REPORT 5 - LER
          $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, job.country, job.division, job.mayor_account, job.manager, 
                                    accounting_gl.level, SUM(accounting_gl.total) as "total"
                                    '))  
                  ->leftJoin('accounting_gl', 'job.job_number' , '=' , 'accounting_gl.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('accounting_gl.period', '>=', $bg_ini_range)
                  ->where('accounting_gl.period', '<=', $bg_end_range)
                  ->groupBy('job.job_number')
                  ->groupBy('accounting_gl.level')
                  ->orderBy('job.job_number', 'asc')
                  ->get();


          $list_hours = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, job.country, job.division, job.mayor_account, job.manager, 
                                    job.job_description, SUM(timekeeping_data.hours) as "hours"
                                    '))  
                  ->leftJoin('timekeeping_data', 'job.job_number' , '=' , 'timekeeping_data.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('timekeeping_data.period', '>=', $bg_ini_range)
                  ->where('timekeeping_data.period', '<=', $bg_end_range)
                  ->groupBy('job.job_number')
                  ->orderBy('job.job_number', 'asc')
                  ->get();


          $data_county=array();
          $data_county_hours=array();
          $data_county_ler=array();

          //Country
          $county_list=$this->listCounty();

          foreach ($county_list as $key => $value) {
            $data_county[$key]['revenue']=0;
            $data_county[$key]['cost']=0;
            $data_county_hours[$key]=0;
            $data_county_ler[$key]=0;
         }     

         foreach ($list as $item) {
            if(isset($county_list[$item->country])){
              if($item->level==1){
                $data_county[$item->country]['revenue']+=$item->total;
              }else{
                $data_county[$item->country]['cost']+=$item->total;
              }
            }
         }

         foreach ($list_hours as $item) {
            if(isset($county_list[$item->country])){
              $data_county_hours[$item->country]+=$item->hours;
            }
         } 

         //LER Calculation
         foreach ($county_list as $key => $value) {
            $gross_profit=abs($data_county[$key]['revenue'])-$data_county[$key]['cost'];
            $hours=(($data_county_hours[$key]!=0)?$data_county_hours[$key]:1);
            $data_county_ler[$key]=$gross_profit/$hours;
         }


          $data_industry=array();
          $data_industry_hours=array();
          $data_industry_ler=array();

          //Industry 
          $industry_list=$this->listIndustry(); 

          foreach ($industry_list as $key => $value) {
            $data_industry[$key]['revenue']=0;
            $data_industry[$key]['cost']=0;
            $data_industry_hours[$key]=0;
         }     

         foreach ($list as $item) {
            if(isset($industry_list[$item->division])){
              if($item->level==1){
                $data_industry[$item->division]['revenue']+=$item->total;
              }else{
                $data_industry[$item->division]['cost']+=$item->total;
              }
            }
         }

         foreach ($list_hours as $item) {
            if(isset($industry_list[$item->division])){
              $data_industry_hours[$item->division]+=$item->hours;
            }
         }     

         //LER Calculation
         foreach ($industry_list as $key => $value) {
            $gross_profit=abs($data_industry[$key]['revenue'])-$data_industry[$key]['cost'];
            $hours=(($data_industry_hours[$key]!=0)?$data_industry_hours[$key]:1);
            $data_industry_ler[$key]=$gross_profit/$hours;
         }    

          $data_mayor=array();
          $data_mayor_hours=array();
          $data_mayor_ler=array();

          //Mayor
          $mayor_list=$this->listMayorAccount(); 

          foreach ($mayor_list as $key => $value) {
              $data_mayor[$key]['revenue']=0;
              $data_mayor[$key]['cost']=0;
              $data_mayor_hours[$key]=0;
           }     

           foreach ($list as $item) {
              if(isset($mayor_list[$item->mayor_account])){
                if($item->level==1){
                  $data_mayor[$item->mayor_account]['revenue']+=$item->total;
                }else{
                  $data_mayor[$item->mayor_account]['cost']+=$item->total;
                }
              }
           }

           foreach ($list_hours as $item) {
              if(isset($mayor_list[$item->mayor_account])){
                $data_mayor_hours[$item->mayor_account]+=$item->hours;
              }
           }   

           //LER Calculation
         foreach ($mayor_list as $key => $value) {
            $gross_profit=abs($data_mayor[$key]['revenue'])-$data_mayor[$key]['cost'];
            $hours=(($data_mayor_hours[$key]!=0)?$data_mayor_hours[$key]:1);
            $data_mayor_ler[$key]=$gross_profit/$hours;
         } 

          $data_manager=array();
          $data_manager_hours=array();
          $data_manager_ler=array();

          //Manager
          $manager_list=$this->listManager(); 

          foreach ($manager_list as $key => $value) {
            $data_manager[$key]['revenue']=0;
            $data_manager[$key]['cost']=0;
            $data_manager_hours[$key]=0;
          }     

         foreach ($list as $item) {
            if(isset($manager_list[$item->manager])){
              if($item->level==1){
                $data_manager[$item->manager]['revenue']+=$item->total;
              }else{
                $data_manager[$item->manager]['cost']+=$item->total;
              }
            }
         }

         foreach ($list_hours as $item) {
            if(isset($manager_list[$item->manager])){
              $data_manager_hours[$item->manager]+=$item->hours;
            }
         }

          //LER Calculation
         foreach ($manager_list as $key => $value) {
            $gross_profit=abs($data_manager[$key]['revenue'])-$data_manager[$key]['cost'];
            $hours=(($data_manager_hours[$key]!=0)?$data_manager_hours[$key]:1);
            $data_manager_ler[$key]=$gross_profit/$hours;
         }   

          return view('report.home_5', ['month_ini'     =>  ($bg_ini_range-$bg_year), 
                                      'month_end'     =>  ($bg_end_range-$bg_year), 
                                      'year_filter'   =>  $bg_year,
                                      'type'          =>  $type,
                                      'county_list'   =>  $county_list,
                                      'industry_list' =>  $industry_list,
                                      'mayor_list'    =>  $mayor_list,
                                      'manager_list'  =>  $manager_list,
                                      'data_county'   =>  $data_county,
                                      'data_industry' =>  $data_industry,
                                      'data_mayor'    =>  $data_mayor,
                                      'data_manager'  =>  $data_manager,
                                      'data_county_ler'   =>  $data_county_ler,
                                      'data_industry_ler' =>  $data_industry_ler,
                                      'data_mayor_ler'    =>  $data_mayor_ler,
                                      'data_manager_ler'  =>  $data_manager_ler,
                                      ]);

        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function getReportCounty(Request $request)
    {
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

        $ini_range=$ini_range+$bg_year;
        $end_range=$end_range+$bg_year;

        $month=($end_range-$ini_range)+1;

        $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, job.country, accounting_gl.level, SUM(accounting_gl.total) as "total"
                                    '))  
                  ->leftJoin('accounting_gl', 'job.job_number' , '=' , 'accounting_gl.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('accounting_gl.period', '>=', $ini_range)
                  ->where('accounting_gl.period', '<=', $end_range)
                  ->groupBy('job.job_number')
                  ->groupBy('accounting_gl.level')
                  ->orderBy('job.job_number', 'asc')
                  ->get();

        //Order Information
        $data=array();          
        
        //Country
        $county_list=$this->listCounty(); 

        foreach ($county_list as $key => $value) {
          $data[$key]['revenue']=0;
          $data[$key]['cost']=0;
       }     

       foreach ($list as $item) {
          if(isset($county_list[$item->country])){
            if($item->level==1){
              $data[$item->country]['revenue']+=$item->total;
            }else{
              $data[$item->country]['cost']+=$item->total;
            }
          }
       }    

       $year_array=array(
        0 => '2016',
        12 => '2017',
       );        


        return view('report.report_view',
          [
          'name'          =>  'County',
          'data'          =>  $data,
          'list'          =>  $county_list,
          'ini_range'     =>  ($ini_range-$bg_year),
          'end_range'     =>  ($end_range-$bg_year),
          'year'          =>  $year_array[$bg_year],
          'type'          =>  1
          ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function getReportIndustry(Request $request)
    {
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

         $ini_range=$ini_range+$bg_year;
        $end_range=$end_range+$bg_year;

        $month=($end_range-$ini_range)+1;

        $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, job.division, accounting_gl.level, SUM(accounting_gl.total) as "total"'))  
                  ->leftJoin('accounting_gl', 'job.job_number' , '=' , 'accounting_gl.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('accounting_gl.period', '>=', $ini_range)
                  ->where('accounting_gl.period', '<=', $end_range)
                  ->groupBy('job.job_number')
                  ->groupBy('accounting_gl.level')
                  ->orderBy('job.job_number', 'asc')
                  ->get();

        //Order Information
        $data=array();          
        
        //Industry 
        $industry_list=$this->listIndustry();  

        foreach ($industry_list as $key => $value) {
          $data[$key]['revenue']=0;
          $data[$key]['cost']=0;
       }     

       foreach ($list as $item) {
          if(isset($industry_list[$item->division])){
            if($item->level==1){
              $data[$item->division]['revenue']+=$item->total;
            }else{
              $data[$item->division]['cost']+=$item->total;
            }
          }
       }     

       $year_array=array(
        0 => '2016',
        12 => '2017',
       );    

        return view('report.report_view',
          [
          'name'          =>  'Industry',
          'data'          =>  $data,
          'list'          =>  $industry_list,
          'ini_range'     =>  ($ini_range-$bg_year),
          'end_range'     =>  ($end_range-$bg_year),
          'year'          =>  $year_array[$bg_year],
          'type'          =>  2
          ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function getReportMayorAccount(Request $request)
    {
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

         $ini_range=$ini_range+$bg_year;
        $end_range=$end_range+$bg_year;

        $month=($end_range-$ini_range)+1;

        $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, job.mayor_account, accounting_gl.level, SUM(accounting_gl.total) as "total"
                                    '))  
                  ->leftJoin('accounting_gl', 'job.job_number' , '=' , 'accounting_gl.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('accounting_gl.period', '>=', $ini_range)
                  ->where('accounting_gl.period', '<=', $end_range)
                  ->groupBy('job.job_number')
                  ->groupBy('accounting_gl.level')
                  ->orderBy('job.job_number', 'asc')
                  ->get();

        //Order Information
        $data=array();          
        
        //Mayor
        $mayor_list=$this->listMayorAccount();

        foreach ($mayor_list as $key => $value) {
          $data[$key]['revenue']=0;
          $data[$key]['cost']=0;
       }     

       foreach ($list as $item) {
          if(isset($mayor_list[$item->mayor_account])){
            if($item->level==1){
              $data[$item->mayor_account]['revenue']+=$item->total;
            }else{
              $data[$item->mayor_account]['cost']+=$item->total;
            }
          }
       }     

       $year_array=array(
        0 => '2016',
        12 => '2017',
       );      


        return view('report.report_view',
          [
          'name'          =>  'Major Account',
          'data'          =>  $data,
          'list'          =>  $mayor_list,
          'ini_range'     =>  ($ini_range-$bg_year),
          'end_range'     =>  ($end_range-$bg_year),
          'year'          =>  $year_array[$bg_year],
          'type'          =>  3
          ] 
        );
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function getReportManager(Request $request)
    {
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

        $month=($end_range-$ini_range)+1;

        $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, job.manager, accounting_gl.level, SUM(accounting_gl.total) as "total"
                                    '))  
                  ->leftJoin('accounting_gl', 'job.job_number' , '=' , 'accounting_gl.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('accounting_gl.period', '>=', $ini_range)
                  ->where('accounting_gl.period', '<=', $end_range)
                  ->groupBy('job.job_number')
                  ->groupBy('accounting_gl.level')
                  ->orderBy('job.job_number', 'asc')
                  ->get();

        //Order Information
        $data=array();          
        
        //Manager
        $manager_list=$this->listManager(); 

        foreach ($manager_list as $key => $value) {
          $data[$key]['revenue']=0;
          $data[$key]['cost']=0;
        }     

       foreach ($list as $item) {
          if(isset($manager_list[$item->manager])){
            if($item->level==1){
              $data[$item->manager]['revenue']+=$item->total;
            }else{
              $data[$item->manager]['cost']+=$item->total;
            }
          }
       }          

       $year_array=array(
        0 => '2016',
        12 => '2017',
       ); 


        return view('report.report_view',
          [
          'name'          =>  'Manager',
          'data'          =>  $data,
          'list'          =>  $manager_list,
          'ini_range'     =>  ($ini_range-$bg_year),
          'end_range'     =>  ($end_range-$bg_year),
          'year'          =>  $year_array[$bg_year],
          'type'          =>  4
          ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function getReportSalesDetail(Request $request)
    {
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

        $ini_range=$ini_range+$bg_year;
        $end_range=$end_range+$bg_year;

        $month=($end_range-$ini_range)+1;

        $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, accounting_gl.level, SUM(accounting_gl.total) as "total"
                                    '))  
                  ->leftJoin('accounting_gl', 'job.job_number' , '=' , 'accounting_gl.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('accounting_gl.period', '>=', $ini_range)
                  ->where('accounting_gl.period', '<=', $end_range)
                  ->groupBy('job.job_number')
                  ->groupBy('accounting_gl.level')
                  ->orderBy('job.job_number', 'asc')
                  ->get();

        $list_job=DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description'))  
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->orderBy('job.job_number', 'asc')
                  ->get();

        //Order Information
        $data=array();          
        
        foreach ($list_job as $item) {
          $data[$item->job_number]['revenue']=0;
          $data[$item->job_number]['cost']=0;
        }
            

       foreach ($list as $item) {
          if($item->level==1){
            $data[$item->job_number]['revenue']+=$item->total;
          }else{
            $data[$item->job_number]['cost']+=$item->total;
          }
          
       }        

       $year_array=array(
        0 => '2016',
        12 => '2017',
       );  


        return view('report.report_sales_detail',
          [
          'name'          =>  'Sales Detail',
          'margin'        =>  25,
          'data'          =>  $data,
          'list'          =>  $list_job,
          'ini_range'     =>  ($ini_range-$bg_year),
          'end_range'     =>  ($end_range-$bg_year),
          'year'          =>  $year_array[$bg_year]
          ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function getReportLerDetail(Request $request)
    {
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

        $ini_range=$ini_range+$bg_year;
        $end_range=$end_range+$bg_year;

        $month=($end_range-$ini_range)+1;

        $list  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, accounting_gl.level, SUM(accounting_gl.total) as "total"
                                    '))  
                  ->leftJoin('accounting_gl', 'job.job_number' , '=' , 'accounting_gl.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('accounting_gl.period', '>=', $ini_range)
                  ->where('accounting_gl.period', '<=', $end_range)
                  ->groupBy('job.job_number')
                  ->groupBy('accounting_gl.level')
                  ->orderBy('job.job_number', 'asc')
                  ->get();

       $list_hours = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, SUM(timekeeping_data.hours) as "hours"
                                    '))  
                  ->leftJoin('timekeeping_data', 'job.job_number' , '=' , 'timekeeping_data.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('timekeeping_data.period', '>=', $ini_range)
                  ->where('timekeeping_data.period', '<=', $end_range)
                  ->groupBy('job.job_number')
                  ->orderBy('job.job_number', 'asc')
                  ->get();



        $list_job=DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description'))  
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->orderBy('job.job_number', 'asc')
                  ->get();

        //Order Information
        $data=array();
        $data_hours=array();          
        
        foreach ($list_job as $item) {
          $data[$item->job_number]['revenue']=0;
          $data[$item->job_number]['cost']=0;
          $data_hours[$item->job_number]=0;
        }
            

       foreach ($list as $item) {
          if($item->level==1){
            $data[$item->job_number]['revenue']+=$item->total;
          }else{
            $data[$item->job_number]['cost']+=$item->total;
          }
          
       }

       foreach ($list_hours as $item) {
          $data_hours[$item->job_number]+=$item->hours;
       }        


       $year_array=array(
        0 => '2016',
        12 => '2017',
       );  


        return view('report.report_ler_detail',
          [
          'name'          =>  'Ler Detail',
          'margin'        =>  2.5,
          'data'          =>  $data,
          'data_hours'    =>  $data_hours,
          'list'          =>  $list_job,
          'ini_range'     =>  ($ini_range-$bg_year),
          'end_range'     =>  ($end_range-$bg_year),
          'year'          =>  $year_array[$bg_year]
          ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function getReportContractStrategicSalesDetail(Request $request)
    {
        
        $ini_range = $request->session()->get('sales_ini_date', function() {
          return date('Y-01-01');
        });

        $end_range = $request->session()->get('sales_end_date', function() {
            return date('Y-12-31');
        });

        

        $list=DB::table('deal')
                    ->select(DB::raw('deal.*,
                      (SELECT deal_stage.label FROM deal_stage WHERE deal.deal_stage=deal_stage.stage_id) as "stage_name",
                      (SELECT deal_pipeline.label FROM deal_pipeline WHERE deal.pipeline_id=deal_pipeline.pipeline_id) as "pipeline_name"'))  
                    ->where('deal.create_date', '>=', $ini_range)
                    ->where('deal.create_date', '<=', $end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->orderBy('deal.create_date','desc')
                    ->get();
         
        $data=array();

        $hubspotowner_list=$this->accountHubspotOwner();

        return view('report.report_contract_strategic_sales_detail',
          [
          'name'          =>  'Contract & Strategic Sales Detail',
          'data'          =>  $data,
          'list'          =>  $list,
          'hubspotowner_list' =>  $hubspotowner_list,
          'ini_range'     =>  $ini_range,
          'end_range'     =>  $end_range,
          ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function getReportCostDetail(Request $request)
    {
        $dateNow = Carbon::now();
        $endIniLag = $dateNow->copy()->subWeek(4);
        
        $list_budget_cost=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                      (SELECT SUM(budget_amount)+SUM(fica)+SUM(futa)+SUM(suta)+SUM(workmans_compensation)+SUM(medicare) FROM labor_tax WHERE labor_tax.job_number=job.job_number AND labor_tax.date >"'.$endIniLag->format('Y-m-d').'" AND labor_tax.date <="'.$dateNow->format('Y-m-d').'") as "total"')) 
                    ->where('job.active', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();  
        
          //28 Days lag(4 week) get budget
            $days_budget=27;        
            
            $badge_1=true;
            $badge_2=false;

            $number_day_month=$dateNow->format('t');
            //Today Date
            $today_day=ltrim($dateNow->format('d'), '0');
            $today_month=ltrim($dateNow->format('m'), '0');
            $today_year=$dateNow->format('Y');
            //Past Date 
            $past_year=0;
            $past_month=0;
            $past_day=0;

            $days_budget-=$today_day;

            if($days_budget>0){
                $badge_2=true;                
                $past_year=$endIniLag->format('Y');
                $past_month=ltrim($endIniLag->format('m'), '0');
                $past_day=$days_budget;
                $budget_total=$days_budget;
                //ltrim($dateNow->format('m'), '0')
            }

            //$dateNow->format('Y-m-d');
            //$endIniLag->format('Y-m-d');

            $list_budget_monthly_badge_1=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                      (SELECT SUM((period'.$today_month.' / 30)*'.$today_day.') FROM budget_monthly WHERE budget_monthly.job_number=job.job_number AND budget_monthly.jc=-1 AND budget_monthly.fiscal_year='.$today_year.') as "total_job"')) 
                    ->where('job.active', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();  

            if($badge_2){
                $list_budget_monthly_badge_2=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                      (SELECT SUM((period'.$past_month.' / 30)*'.$past_day.') FROM budget_monthly WHERE budget_monthly.job_number=job.job_number AND budget_monthly.jc=-1 AND budget_monthly.fiscal_year='.$past_year.') as "total_job"')) 
                    ->where('job.active', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();                
            }


            $list_actual_cost  = DB::table('job')
                                ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                                  (SELECT SUM(expense.amount) FROM expense LEFT JOIN report_account ON expense.account_number=report_account.account_number WHERE expense.job_number=job.job_number AND report_account.level<>1 AND expense.posting_date >="'.$endIniLag->format('Y-m-d').'" AND expense.posting_date <="'.$dateNow->format('Y-m-d').'") as "total"'))  
                                ->where('job.active', 1)
                                ->groupBy('job.job_number')
                                ->groupBy('job.job_description')
                                ->orderBy('job.job_number', 'asc')
                                ->get();

        $list_job=DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description,
                                    (SELECT CONCAT_WS(" ",first_name, last_name)  FROM users WHERE manager_id=job.manager LIMIT 1) as "manager_name"
                                    '))  
                  ->where('job.active', 1)
                  ->orderBy('job.job_number', 'asc')
                  ->get();

        //Order Information
        $data=array();          
        
        foreach ($list_job as $item) {
          $data[$item->job_number]['budget']=0;
          $data[$item->job_number]['cost']=0;
        }
            
       foreach ($list_budget_cost as $item) {
          $data[$item->job_number]['budget']+=$item->total;
       }

       foreach ($list_budget_monthly_badge_1 as $item) {
          $data[$item->job_number]['budget']+=$item->total_job;
       }

       if($badge_2){
         foreach ($list_budget_monthly_badge_2 as $item) {
            $data[$item->job_number]['budget']+=$item->total_job;
         }
       }

       foreach ($list_actual_cost as $item) {
          $data[$item->job_number]['cost']+=$item->total;
       }          


        return view('report.report_cost_detail',
          [
          'name'          =>  'Cost vrs Target Detail',
          'data'          =>  $data,
          'list'          =>  $list_job,
          'ini_range'     =>  $endIniLag->format('m/d/Y'),
          'end_range'     =>  $dateNow->format('m/d/Y'),
          ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function getReportTimekeepingDetail(Request $request)
    {
        $tk_ini_range = $request->session()->get('tk_ini_date', function() {
          $dateRange = Carbon::now();
          $iniDate=$dateRange->copy()->subWeek(8);
          return $iniDate->format('Y-m-d');
        });

        $tk_end_range = $request->session()->get('tk_end_date', function() {
            $dateRange = Carbon::now();
            $endDate=$dateRange->copy()->subDay(1);
            return $endDate->format('Y-m-d');
        });

        $list_budget=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                    (SELECT SUM(labor_tax.budget_hour) FROM labor_tax WHERE labor_tax.job_number=job.job_number AND labor_tax.date >="'.$tk_ini_range.'" AND labor_tax.date <="'.$tk_end_range.'") as "total"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

          $list_actual=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                      (SELECT SUM(timekeeping.hours) FROM timekeeping WHERE timekeeping.job_number=job.job_number AND timekeeping.work_date >="'.$tk_ini_range.'" AND timekeeping.work_date <="'.$tk_end_range.'") as "total"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

        $list_job=DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description'))  
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->orderBy('job.job_number', 'asc')
                  ->get();

        //Order Information
        $data=array();          
        
        foreach ($list_job as $item) {
          $data[$item->job_number]['actual']=0;
          $data[$item->job_number]['budget']=0;
        }
            

       foreach ($list_actual as $item) {
            $data[$item->job_number]['actual']+=$item->total;
       }

       foreach ($list_budget as $item) {
            $data[$item->job_number]['budget']+=$item->total;
       }          


        return view('report.report_timekeeping_detail',
          [
          'name'          =>  'Timekeeping Detail',
          'data'          =>  $data,
          'list'          =>  $list_job,
          'ini_range'     =>  $tk_ini_range,
          'end_range'     =>  $tk_end_range,
          ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function getReportActualBudget(Request $request, $type)
    {
      

        $bg_ini_range = $request->session()->get('rpt_ini_date', function() {
            return '1';
        });

        $bg_end_range = $request->session()->get('rpt_end_date', function() {
            return '12';
        });

         //Year
        $bg_year = $request->session()->get('rpt_year', function() {
            return '0';
        });

        //Calculate values
        $bg_ini_range=$bg_ini_range+$bg_year;
        $bg_end_range=$bg_end_range+$bg_year;

        $month=($bg_end_range-$bg_ini_range)+1;

        $list_budget=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                    (SELECT -SUM(budget_data.total) FROM budget_data WHERE budget_data.job_number=job.job_number AND budget_data.period >='.$bg_ini_range.' AND budget_data.period <='.$bg_end_range.') as "total",
                                    (SELECT -(budget_gl.period1*'.$month.') FROM budget_gl WHERE budget_gl.job_number=job.job_number AND budget_gl.period ='.$bg_year.') as "total_job"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

          $list_actual=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                      (SELECT -SUM(actual_gl.actual_total) FROM actual_gl WHERE actual_gl.job_number=job.job_number AND actual_gl.period >='.$bg_ini_range.' AND actual_gl.period <='.$bg_end_range.') as "total"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

        
        $data_budget=array();          
        $data_actual=array();
        
        if($type==1){
          $name="County";
          $list=$this->listCounty();
          foreach ($list as $key => $value) {
            $data_budget[$key]=0;
            $data_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($list[$item->country])){
              $data_budget[$item->country]+=$item->total_job+$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($list[$item->country])){
              $data_actual[$item->country]+=$item->total;
            }
          }

        }elseif ($type==2) {
          $name="Industry";  
          $list=$this->listIndustry();  
          foreach ($list as $key => $value) {
            $data_budget[$key]=0;
            $data_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($list[$item->division])){
              $data_budget[$item->division]+=$item->total_job+$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($list[$item->division])){
              $data_actual[$item->division]+=$item->total;
            }
          }

        }elseif ($type==3) {
          $name="Major Account";
          $list=$this->listMayorAccount();
          foreach ($list as $key => $value) {
            $data_budget[$key]=0;
            $data_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($list[$item->mayor_account])){
              $data_budget[$item->mayor_account]+=$item->total_job+$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($list[$item->mayor_account])){
              $data_actual[$item->mayor_account]+=$item->total;
            }
          }

        }elseif ($type==4) {
          $name="Manager";
          $list=$this->listManager();
          foreach ($list as $key => $value) {
            $data_budget[$key]=0;
            $data_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($list[$item->manager])){
              $data_budget[$item->manager]+=$item->total_job+$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($list[$item->manager])){
              $data_actual[$item->manager]+=$item->total;
            }
          }

        }

        $year_array=array(
          0 => '2016',
          12 => '2017',
         );  
    
        return view('report.report_actual_budget_view',
          [
          'name'          =>  $name,
          'data_budget'   =>  $data_budget,
          'data_actual'   =>  $data_actual,
          'list'          =>  $list,
          'ini_range'     =>  ($bg_ini_range-$bg_year),
          'end_range'     =>  ($bg_end_range-$bg_year),
          'year'          =>  $year_array[$bg_year],
          'type'          =>  $type
          ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function getReportLer(Request $request, $type)
    {
      

        $bg_ini_range = $request->session()->get('rpt_ini_date', function() {
            return '1';
        });

        $bg_end_range = $request->session()->get('rpt_end_date', function() {
            return '12';
        });

         //Year
        $bg_year = $request->session()->get('rpt_year', function() {
            return '0';
        });

        //Calculate values
        $bg_ini_range=$bg_ini_range+$bg_year;
        $bg_end_range=$bg_end_range+$bg_year;

        $month=($bg_end_range-$bg_ini_range)+1;

        $list_accounting  = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, job.country, job.division, job.mayor_account, job.manager, 
                                    accounting_gl.level, SUM(accounting_gl.total) as "total"
                                    '))  
                  ->leftJoin('accounting_gl', 'job.job_number' , '=' , 'accounting_gl.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('accounting_gl.period', '>=', $bg_ini_range)
                  ->where('accounting_gl.period', '<=', $bg_end_range)
                  ->groupBy('job.job_number')
                  ->groupBy('accounting_gl.level')
                  ->orderBy('job.job_number', 'asc')
                  ->get();


          $list_hours = DB::table('job')
                  ->select(DB::raw('job.job_number as "job_number", 
                                    job.job_description, job.country, job.division, job.mayor_account, job.manager, 
                                    job.job_description, SUM(timekeeping_data.hours) as "hours"
                                    '))  
                  ->leftJoin('timekeeping_data', 'job.job_number' , '=' , 'timekeeping_data.job_number')
                  ->where('job.active', 1)
                  ->where('job.report', 1)
                  ->where('timekeeping_data.period', '>=', $bg_ini_range)
                  ->where('timekeeping_data.period', '<=', $bg_end_range)
                  ->groupBy('job.job_number')
                  ->orderBy('job.job_number', 'asc')
                  ->get();

        
        $data=array();          
        $data_hours=array();
        $data_ler=array();
        
        if($type==1){
          $name="County";
          $list=$this->listCounty();
          

          foreach ($list as $key => $value) {
            $data[$key]['revenue']=0;
            $data[$key]['cost']=0;
            $data_hours[$key]=0;
            $data_ler[$key]=0;
         }     

         foreach ($list_accounting as $item) {
            if(isset($list[$item->country])){
              if($item->level==1){
                $data[$item->country]['revenue']+=$item->total;
              }else{
                $data[$item->country]['cost']+=$item->total;
              }
            }
         }

         foreach ($list_hours as $item) {
            if(isset($list[$item->country])){
              $data_hours[$item->country]+=$item->hours;
            }
         } 

         //LER Calculation
         foreach ($list as $key => $value) {
            $gross_profit=abs($data[$key]['revenue'])-$data[$key]['cost'];
            $hours=(($data_hours[$key]!=0)?$data_hours[$key]:1);
            $data_ler[$key]=$gross_profit/$hours;
         }  

        }elseif ($type==2) {
          $name="Industry";  
          $list=$this->listIndustry();  

          foreach ($list as $key => $value) {
            $data[$key]['revenue']=0;
            $data[$key]['cost']=0;
            $data_hours[$key]=0;
            $data_ler[$key]=0;
         }     

         foreach ($list_accounting as $item) {
            if(isset($list[$item->division])){
              if($item->level==1){
                $data[$item->division]['revenue']+=$item->total;
              }else{
                $data[$item->division]['cost']+=$item->total;
              }
            }
         }

         foreach ($list_hours as $item) {
            if(isset($list[$item->division])){
              $data_hours[$item->division]+=$item->hours;
            }
         } 

         //LER Calculation
         foreach ($list as $key => $value) {
            $gross_profit=abs($data[$key]['revenue'])-$data[$key]['cost'];
            $hours=(($data_hours[$key]!=0)?$data_hours[$key]:1);
            $data_ler[$key]=$gross_profit/$hours;
         }


        }elseif ($type==3) {
          $name="Major Account";
          $list=$this->listMayorAccount();

          foreach ($list as $key => $value) {
            $data[$key]['revenue']=0;
            $data[$key]['cost']=0;
            $data_hours[$key]=0;
            $data_ler[$key]=0;
         }     

         foreach ($list_accounting as $item) {
            if(isset($list[$item->mayor_account])){
              if($item->level==1){
                $data[$item->mayor_account]['revenue']+=$item->total;
              }else{
                $data[$item->mayor_account]['cost']+=$item->total;
              }
            }
         }

         foreach ($list_hours as $item) {
            if(isset($list[$item->mayor_account])){
              $data_hours[$item->mayor_account]+=$item->hours;
            }
         } 

         //LER Calculation
         foreach ($list as $key => $value) {
            $gross_profit=abs($data[$key]['revenue'])-$data[$key]['cost'];
            $hours=(($data_hours[$key]!=0)?$data_hours[$key]:1);
            $data_ler[$key]=$gross_profit/$hours;
         }


        }elseif ($type==4) {
          $name="Manager";
          $list=$this->listManager();

          foreach ($list as $key => $value) {
            $data[$key]['revenue']=0;
            $data[$key]['cost']=0;
            $data_hours[$key]=0;
            $data_ler[$key]=0;
         }     

         foreach ($list_accounting as $item) {
            if(isset($list[$item->manager])){
              if($item->level==1){
                $data[$item->manager]['revenue']+=$item->total;
              }else{
                $data[$item->manager]['cost']+=$item->total;
              }
            }
         }

         foreach ($list_hours as $item) {
            if(isset($list[$item->manager])){
              $data_hours[$item->manager]+=$item->hours;
            }
         } 

         //LER Calculation
         foreach ($list as $key => $value) {
            $gross_profit=abs($data[$key]['revenue'])-$data[$key]['cost'];
            $hours=(($data_hours[$key]!=0)?$data_hours[$key]:1);
            $data_ler[$key]=$gross_profit/$hours;
         }

        }

        $year_array=array(
          0 => '2016',
          12 => '2017',
         );  
    
        return view('report.report_ler_view',
          [
          'name'          =>  $name,
          'data'          =>  $data,
          'data_hours'    =>  $data_hours,
          'data_ler'      =>  $data_ler,
          'list'          =>  $list,
          'ini_range'     =>  ($bg_ini_range-$bg_year),
          'end_range'     =>  ($bg_end_range-$bg_year),
          'year'          =>  $year_array[$bg_year],
          'type'          =>  $type
          ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function getReportTimekeeping(Request $request, $type)
    {
      
        $tk_ini_range = $request->session()->get('tk_ini_date', function() {
          $dateRange = Carbon::now();
          $iniDate=$dateRange->copy()->subWeek(8);
          return $iniDate->format('Y-m-d');
        });

        $tk_end_range = $request->session()->get('tk_end_date', function() {
             $dateRange = Carbon::now();
            $endDate=$dateRange->copy()->subDay(1);
            return $endDate->format('Y-m-d');
        });




        $list_budget=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                    (SELECT SUM(labor_tax.budget_hour) FROM labor_tax WHERE labor_tax.job_number=job.job_number AND labor_tax.date >="'.$tk_ini_range.'" AND labor_tax.date <="'.$tk_end_range.'") as "total"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

          $list_actual=DB::table('job')
                    ->select(DB::raw('job.job_number as "job_number", job.job_description, job.country, job.division, job.mayor_account, job.manager,
                                      (SELECT SUM(timekeeping.hours) FROM timekeeping WHERE timekeeping.job_number=job.job_number AND timekeeping.work_date >="'.$tk_ini_range.'" AND timekeeping.work_date <="'.$tk_end_range.'") as "total"'))  
                    ->where('job.active', 1)
                    ->where('job.report', 1)
                    ->groupBy('job.job_number')
                    ->groupBy('job.job_description')
                    ->orderBy('job.job_number', 'asc')
                    ->get();

        
        $data_budget=array();          
        $data_actual=array();
        
        if($type==1){
          $name="County";
          $list=$this->listCounty();
          foreach ($list as $key => $value) {
            $data_budget[$key]=0;
            $data_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($list[$item->country])){
              $data_budget[$item->country]+=$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($list[$item->country])){
              $data_actual[$item->country]+=$item->total;
            }
          }

        }elseif ($type==2) {
          $name="Industry";  
          $list=$this->listIndustry();  
          foreach ($list as $key => $value) {
            $data_budget[$key]=0;
            $data_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($list[$item->division])){
              $data_budget[$item->division]+=$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($list[$item->division])){
              $data_actual[$item->division]+=$item->total;
            }
          }

        }elseif ($type==3) {
          $name="Major Account";
          $list=$this->listMayorAccount();
          foreach ($list as $key => $value) {
            $data_budget[$key]=0;
            $data_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($list[$item->mayor_account])){
              $data_budget[$item->mayor_account]+=$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($list[$item->mayor_account])){
              $data_actual[$item->mayor_account]+=$item->total;
            }
          }

        }elseif ($type==4) {
          $name="Manager";
          $list=$this->listManager();
          foreach ($list as $key => $value) {
            $data_budget[$key]=0;
            $data_actual[$key]=0;
          }

          foreach ($list_budget as $item) {
            if(isset($list[$item->manager])){
              $data_budget[$item->manager]+=$item->total;
            }
          } 

          foreach ($list_actual as $item) {
            if(isset($list[$item->manager])){
              $data_actual[$item->manager]+=$item->total;
            }
          }

        }

      

        return view('report.report_timekeeping_view',
          [
          'name'          =>  $name,
          'data_budget'   =>  $data_budget,
          'data_actual'   =>  $data_actual,
          'list'          =>  $list,
          'ini_range'     =>  $tk_ini_range,
          'end_range'     =>  $tk_end_range,
          'type'          =>  $type
          ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function getReportContractStrategicSales(Request $request, $type)
    {
      

        $sales_ini_range = $request->session()->get('sales_ini_date', function() {
            return date('Y-01-01');
        });

        $sales_end_range = $request->session()->get('sales_end_date', function() {
            return date('Y-12-31');
        });

        $data=array();
        $data_won=array();
        $data_forecast=array();

        if($type==4){
          //Manager
          $name="Manager";
          $list=$this->accountManager(); 

          foreach ($list as $key => $value) {
            $data[$value]=0;
            $data_won[$value]=0;
            $data_forecast[$value]=0;
          }

          $list_deal=DB::table('deal')
                    ->select(DB::raw('deal.account_manager, SUM(amount) as "total"'))  
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.account_manager')
                    ->get();

          $list_deal_won=DB::table('deal')
                    ->select(DB::raw('deal.account_manager, SUM(amount) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->where('deal_stage.close_won', '=', 1)
                    ->groupBy('deal.account_manager')
                    ->get();

          $list_deal_forecast=DB::table('deal')
                    ->select(DB::raw('deal.account_manager, SUM(amount*deal_stage.probability) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.account_manager')
                    ->get();

          foreach ($list_deal as $value) {
            $manager=$value->account_manager;
            if($manager=="")
              $manager="None";

            $data[$manager]=$value->total;  
          }

          foreach ($list_deal_won as $value) {
            $manager=$value->account_manager;
            if($manager=="")
              $manager="None";

            $data_won[$manager]=$value->total;  
          }

          foreach ($list_deal_forecast as $value) {
            $manager=$value->account_manager;
            if($manager=="")
              $manager="None";

            $data_forecast[$manager]=$value->total;  
          }
      }elseif ($type==2) {
          //Industry
          $name="Industry";
          $list=$this->accountIndustry(); 

          foreach ($list as $key => $value) {
            $data[$value]=0;
            $data_won[$value]=0;
            $data_forecast[$value]=0;
          }

          $list_deal=DB::table('deal')
                    ->select(DB::raw('deal.deal_vertical, SUM(amount) as "total"'))  
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.deal_vertical')
                    ->get();

          $list_deal_won=DB::table('deal')
                    ->select(DB::raw('deal.deal_vertical, SUM(amount) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->where('deal_stage.close_won', '=', 1)
                    ->groupBy('deal.deal_vertical')
                    ->get();

          $list_deal_forecast=DB::table('deal')
                    ->select(DB::raw('deal.deal_vertical, SUM(amount*deal_stage.probability) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.deal_vertical')
                    ->get();

          foreach ($list_deal as $value) {
            $industry=$value->deal_vertical;
            if($industry=="")
              $industry="None";

            $data[$industry]=$value->total;  
          }

          foreach ($list_deal_won as $value) {
            $industry=$value->deal_vertical;
            if($industry=="")
              $industry="None";

            $data_won[$industry]=$value->total;  
          }

          foreach ($list_deal_forecast as $value) {
            $industry=$value->deal_vertical;
            if($industry=="")
              $industry="None";

            $data_forecast[$industry]=$value->total;  
          }
      }elseif ($type==3) {
          $name="Pipeline";
          $list=$this->accountPipeline(); 

          foreach ($list as $key => $value) {
            $data[$key]=0;
            $data_won[$key]=0;
            $data_forecast[$key]=0;
          }

          $list_deal=DB::table('deal')
                    ->select(DB::raw('deal.pipeline_id, SUM(amount) as "total"'))  
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.pipeline_id')
                    ->get();

          $list_deal_won=DB::table('deal')
                    ->select(DB::raw('deal.pipeline_id, SUM(amount) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->where('deal_stage.close_won', '=', 1)
                    ->groupBy('deal.pipeline_id')
                    ->get();

          $list_deal_forecast=DB::table('deal')
                    ->select(DB::raw('deal.pipeline_id, SUM(amount*deal_stage.probability) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.pipeline_id')
                    ->get();

          foreach ($list_deal as $value) {
            $pipeline_id=$value->pipeline_id;
            if($pipeline_id=="")
              $pipeline_id="None";

            $data[$pipeline_id]=$value->total;  
          }

          foreach ($list_deal_won as $value) {
            $pipeline_id=$value->pipeline_id;
            if($pipeline_id=="")
              $pipeline_id="None";

            $data_won[$pipeline_id]=$value->total;  
          }

          foreach ($list_deal_forecast as $value) {
            $pipeline_id=$value->pipeline_id;
            if($pipeline_id=="")
              $pipeline_id="None";

            $data_forecast[$pipeline_id]=$value->total;  
          }
      }elseif ($type==1) {
          //Hubspot Owner
          $name="Sales Rep";
          $list=$this->accountHubspotOwner(); 

          foreach ($list as $key => $value) {
            $data[$key]=0;
            $data_won[$key]=0;
            $data_forecast[$key]=0;
          }

          $list_deal=DB::table('deal')
                    ->select(DB::raw('deal.hubspot_owner_id, SUM(amount) as "total"'))  
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.hubspot_owner_id')
                    ->get();

          $list_deal_won=DB::table('deal')
                    ->select(DB::raw('deal.hubspot_owner_id, SUM(amount) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->where('deal_stage.close_won', '=', 1)
                    ->groupBy('deal.hubspot_owner_id')
                    ->get();

          $list_deal_forecast=DB::table('deal')
                    ->select(DB::raw('deal.hubspot_owner_id, SUM(amount*deal_stage.probability) as "total"'))  
                    ->leftJoin('deal_stage', 'deal_stage.stage_id' , '=' , 'deal.deal_stage')
                    ->where('deal.create_date', '>=', $sales_ini_range)
                    ->where('deal.create_date', '<=', $sales_end_range)
                    ->whereIn('deal.pipeline_id', $this->pipeline_valid)
                    ->groupBy('deal.hubspot_owner_id')
                    ->get();

          foreach ($list_deal as $value) {
            $hubspot_owner_id=$value->hubspot_owner_id;
            if($hubspot_owner_id=="")
              $hubspot_owner_id="None";

            $data[$hubspot_owner_id]=$value->total;  
          }

          foreach ($list_deal_won as $value) {
            $hubspot_owner_id=$value->hubspot_owner_id;
            if($hubspot_owner_id=="")
              $hubspot_owner_id="None";

            $data_won[$hubspot_owner_id]=$value->total;  
          }

          foreach ($list_deal_forecast as $value) {
            $hubspot_owner_id=$value->hubspot_owner_id;
            if($hubspot_owner_id=="")
              $hubspot_owner_id="None";

            $data_forecast[$hubspot_owner_id]=$value->total;  
          }
      }
    
        return view('report.report_contract_strategic_on_sales_view',
          [
          'name'               =>  $name,
          'list'               =>  $list,
          'list_deal'          =>  $list_deal,
          'list_deal_won'      =>  $list_deal_won,
          'list_deal_forecast' =>  $list_deal_forecast,
          'data'          =>  $data,
          'data_forecast' =>  $data_forecast,
          'data_won'      =>  $data_won,
          'ini_range'     =>  $sales_ini_range,
          'end_range'     =>  $sales_end_range,
          'type'          =>  $type
          ] 
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    private function listCounty()
    {
      return array(
            '1'   =>  'Broward',
            '2'   =>  'Miami-Dade',
            '3'   =>  'Palm Beach County',   
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    private function listIndustry()
    {
      return array(
            '1'   =>  'Healthcare',
            '3'   =>  'Education',
            '4'   =>  'Commercial',
            '5'   =>  'Hospitality',
            '6'   =>  'Government',
            '7'   =>  'PublicVenue',
            '8'   =>  'Retail',
            '9'   =>  'Industrial',
            '10'  =>  'Event',
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    private function listManager()
    {
      return array(
              '9'     =>  'Cesar Pichardo',
              '7'     =>  'Ed Cozzi',
              '15'    =>  'Edelma Cabrera',
              '1'     =>  'Jack Singh',
              '4'     =>  'Jorge Castro',
              '10'    =>  'Julio Morales',
              '5'     =>  'Omar Diaz',
              '3'     =>  'Thomas Owen',
              '8'     =>  'Varouj Aghyarian',
              '91'    =>  'None'
          );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    private function listMayorAccount()
    {
      return array(
            '11'    =>  'Art Miami',
            '5'     =>  'Broward College',
            '4'     =>  'Broward Healthcare',
            '10'    =>  'Enterprise Holdings',
            '1'     =>  'FAU Boca Raton',
            '9'     =>  'FAU Broward',
            '2'     =>  'FAU Jupiter',
            '7'     =>  'FIU',
            '3'     =>  'Memorial Healthcare',
            '6'     =>  'Miami Dade College',
            '12'    =>  'St. Andrews',
            '8'     =>  'Woodfield Country Club',
            '0'     =>  'None',
        ); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
     
    private function accountManager()
    {
        return array(
            'Cesar Pichardo'   =>  'Cesar Pichardo',
            'Jack Singh'       =>  'Jack Singh',
            'Jorge Castro'     =>  'Jorge Castro',
            'Omar Diaz'        =>  'Omar Diaz',
            'Thomas Owen'      =>  'Thomas Owen',
            'Varouj Aghyarian' =>  'Varouj Aghyarian',
        );
        //account_manager
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
     
    private function accountIndustry()
    {
        return array(
            'Healthcare'      =>  'Healthcare',
            'Education'       =>  'Education',
            'Hospitality'     =>  'Hospitality',
            'Public Venue'    =>  'Public Venue',
            'Industrial'      =>  'Industrial',
            'Commercial'      =>  'Commercial',
            'Other'           =>  'Other',
        );
        //deal_vertical
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
     
    private function accountPipeline()
    {
        $data=array();
        $pipeline_list=DB::table('deal_pipeline')
                    ->select(DB::raw('*'))  
                    ->where('active', '=', 1)
                    ->whereIn('pipeline_id', $this->pipeline_valid)
                    ->orderBy('display_order', 'asc')
                    ->get();

        foreach ($pipeline_list as  $value) {
          $data[$value->pipeline_id]=$value->label;
        }

        return $data;        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
     
    private function accountHubspotOwner()
    {
        return array(
            '7458799'      =>  'Elizabeth Mesegue',
            '7270052'      =>  'Marcell Haywood',
            '14699072'     =>  'Omar Finochio',
            '13015189'     =>  'Mark Yohannan',
            '16366970'     =>  'Catherine Gonzalez'  
        );
        //deal_vertical
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function changeMonth(Request $request, $type, $value)
    {
      if($type==1){
        $request->session()->put('rpt_ini_date', $value);  
      }elseif ($type==2) {
        $request->session()->put('rpt_end_date', $value);
      }else{
        $request->session()->put('rpt_year', $value);
      }
      
      
      return Response::json(
              array('status'        => $value)
      );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function changeFilterValues(Request $request, $ini_month, $end_month, $year)
    {
      
      $request->session()->put('rpt_ini_date', $ini_month);  
      $request->session()->put('rpt_end_date', $end_month);
      $request->session()->put('rpt_year', $year);
      $status=1;
      
      return Response::json(
              array('status'        => $status)
      );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function changeDate(Request $request, $type, $date)
    {
      
      $date_str = DateTime::createFromFormat('m-d-Y', $date);
      

      if($type==1){
        $request->session()->put('tk_ini_date', $date_str->format('Y-m-d'));  
      }else{
        $request->session()->put('tk_end_date', $date_str->format('Y-m-d'));
      }
      
      return Response::json(
              array('status'        => $date)
      );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function changeFilterDate(Request $request,  $ini_date, $end_date)
    {
      
      $date_ini_str = DateTime::createFromFormat('m-d-Y', $ini_date);
      $date_end_str = DateTime::createFromFormat('m-d-Y', $end_date);
      
      $request->session()->put('tk_ini_date', $date_ini_str->format('Y-m-d'));  
      $request->session()->put('tk_end_date', $date_end_str->format('Y-m-d'));
      
      $status=1;
      
      return Response::json(
              array('status'        => $status)
      );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function changeSalesDate(Request $request, $type, $date)
    {
      
      $date_str = DateTime::createFromFormat('m-d-Y', $date);
      

      if($type==1){
        $request->session()->put('sales_ini_date', $date_str->format('Y-m-d'));  
      }else{
        $request->session()->put('sales_end_date', $date_str->format('Y-m-d'));
      }
      
      return Response::json(
              array('status'        => $date)
      );

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function changeFilterSalesDate(Request $request, $ini_date, $end_date)
    {
      
      $date_ini_str = DateTime::createFromFormat('m-d-Y', $ini_date);
      $date_end_str = DateTime::createFromFormat('m-d-Y', $end_date);
      
      $request->session()->put('sales_ini_date', $date_ini_str->format('Y-m-d'));  
      $request->session()->put('sales_end_date', $date_end_str->format('Y-m-d'));
      
      $status=1;
      
      return Response::json(
              array('status'        => $status)
      );
      
    }




}
