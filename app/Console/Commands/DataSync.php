<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use File;
use Excel;
use DateTime;
use App\Schedule_task;
use App\Job;
use App\User;
use App\UserProfile;
use App\Labor_tax;
use App\Expense;
use App\Billable_hours;
use App\Budget_monthly;
use App\Budget;
use App\Timekeeping_data;

use App\File_load;
use GuzzleHttp\Client;

class DataSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasync {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync information from WINTEAM';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(160);
        $type=$this->argument('type');
        $path_prefix="../pm/";
        //$path_prefix="public/";

        $schedule_task=Schedule_task::create([
            'name'         => 'task'.strtotime("now"),
            'number'       => 80,
            'type'         => $type  
        ]);

        //Job Sync
        if($type==1){
            $directory=$path_prefix."data/billable_hours";
            $files = File::allFiles($directory);

            foreach ($files as $file) {

                
                $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 1)
                                        ->exists();
                if(!$file_exists){                        
                    $count_insert=0;
                    $count_update=0;
                    Billable_hours::truncate();

                    $data = Excel::load($file)->toArray();
                    
                    foreach ($data as $row) {
                    
                        $data_value=$row;
                        //Insert Profile
                        
                        $str = $data_value['workdate'];
                        $date = DateTime::createFromFormat('m/d/Y', $str);


                        if (Billable_hours::where('employee_number', '=', $data_value['employeenumber'])->where('job_number', '=', $data_value['jobnumber'])->where('work_date', '=', $date->format('Y-m-d'))->count() > 0) {
                            Billable_hours::create([
                                'employee_number'   => (isset($data_value['employeenumber'])?$data_value['employeenumber']:""), 
                                'job_number'        => isset($data_value['jobnumber'])?$data_value['jobnumber']:"", 
                                'work_date'         => $date->format('Y-m-d'),
                                'regular_hours'     => isset($data_value['hours'])?$data_value['hours']:"", 
                                'lunch_hours'       => isset($data_value['lunch'])?$data_value['lunch']:"",        
                                'pay_rate'          => isset($data_value['payrate'])?$data_value['payrate']:"",
                                'overtime_hours'    => isset($data_value['overtimehours'])?$data_value['overtimehours']:"",        
                                'status'            =>  2
                            ]);
                            $count_update++;
                        }else{    
                            Billable_hours::create([
                                'employee_number'   => (isset($data_value['employeenumber'])?$data_value['employeenumber']:""), 
                                'job_number'        => isset($data_value['jobnumber'])?$data_value['jobnumber']:"", 
                                'work_date'         => $date->format('Y-m-d'),
                                'regular_hours'     => isset($data_value['hours'])?$data_value['hours']:"", 
                                'lunch_hours'       => isset($data_value['lunch'])?$data_value['lunch']:"",        
                                'pay_rate'          => isset($data_value['payrate'])?$data_value['payrate']:"",
                                'overtime_hours'    => isset($data_value['overtimehours'])?$data_value['overtimehours']:"",        
                            ]);
                            $count_insert++;
                        }
                        
                    }

                    //Insert file record
                    File_load::create([
                        'file_name'   =>    $file,
                        'type'        =>    1, 
                        'count_insert'=>    $count_insert,
                        'count_update'=>    $count_update,      
                    ]);


                }
                    

            }
        }elseif($type==2){
            $directory=$path_prefix."data/job";

            $files = File::allFiles($directory);

            foreach ($files as $file) {
                 $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 2)
                                        ->exists();
                if(!$file_exists){  
                    $count_insert=0;
                    $count_update=0;
                    $data = Excel::load($file)->toArray();
                    
                    foreach ($data as $row) {

                            $data_value=$row;

                            $latitude=0;
                            $longitude=0;

                            if(isset($data_value['point'])){
                                if(trim($data_value['point'])!=""){
                                    $str_point=trim(str_replace('POINT', '', $data_value['point']));
                                    $str_point=trim(str_replace('(', '', $str_point));
                                    $str_point=trim(str_replace(')', '', $str_point));
                                    $points=explode(" ", $str_point);
                                    if(isset($points[0]))
                                        $longitude=trim($points[0]);
                                    else
                                        $longitude=-1;

                                    if(isset($points[1]))
                                        $latitude=trim($points[1]);              
                                    else
                                        $latitude=-1;
                                }
                            }
                            
                            //Verify Job Exists
                            if (Job::where('job_number', '=', $data_value['jobnumber'])->count() > 0) {
                               //Update item
                                $job= Job::where('job_number', $data_value['jobnumber'])->first();

                                $job->job_description   = $data_value['jobdescription'];
                                $job->type_number       = $data_value['typeid'];
                                $job->supervisor_id     = $data_value['supervisorid'];
                                $job->region            = $data_value['tier1id'];
                                $job->country           = $data_value['tier2id']; 
                                $job->division          = $data_value['tier3id'];
                                $job->manager           = $data_value['tier5id']; 
                                $job->service           = $data_value['tier6id'];
                                $job->mayor_account     = $data_value['tier7id'];        
                                $job->area_supervisor   = $data_value['tier8id'];        
                                $job->is_parent         = (($data_value['parentjobnumber']!=0)?1:0);
                                $job->parent_job        = $data_value['parentjobnumber'];
                                $job->active            = $data_value['active'];
                                $job->address1          = $data_value['jobaddress1'];
                                $job->address2          = $data_value['jobaddress2'];
                                $job->city              = $data_value['jobcity'];
                                $job->state             = $data_value['jobstate'];
                                $job->zip               = $data_value['jobzip'];
                                $job->longitude         = $longitude;
                                $job->latitude          = $latitude;
                                
                                        
                                $job->save();
                                $count_update++;
                            }else{
                                
                                //Insert item
                                Job::create([
                                    'job_number'        => $data_value['jobnumber'],    
                                    'job_description'   => $data_value['jobdescription'], 
                                    'type_number'       => $data_value['typeid'],
                                    'supervisor_id'     => $data_value['supervisorid'], 
                                    'region'            => $data_value['tier1id'], 
                                    'country'           => $data_value['tier2id'], 
                                    'division'          => $data_value['tier3id'],
                                    'manager'           => $data_value['tier5id'], 
                                    'service'           => $data_value['tier6id'],
                                    'mayor_account'     => $data_value['tier7id'],       
                                    'area_supervisor'   => $data_value['tier8id'],         
                                    'is_parent'         => (($data_value['parentjobnumber']!=0)?1:0),
                                    'parent_job'        => $data_value['parentjobnumber'],
                                    'active'            => $data_value['active'],
                                    'address1'          => $data_value['jobaddress1'],
                                    'address2'          => $data_value['jobaddress2'],
                                    'city'              => $data_value['jobcity'],
                                    'state'             => $data_value['jobstate'],
                                    'zip'               => $data_value['jobzip'],
                                    'longitude'         => $longitude,
                                    'latitude'          => $latitude  
                                ]);    
                                $count_insert++;
                            }                            
                    }

                    //Insert file
                    File_load::create([
                        'file_name'   =>    $file,
                        'type'        =>    2,   
                        'count_insert'=>    $count_insert,
                        'count_update'=>    $count_update,      

                    ]);
                }
            }
        }elseif ($type==3) {
            $directory=$path_prefix."data/expense";
            $files = File::allFiles($directory);

            foreach ($files as $file) {
                 $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 3)
                                        ->exists();
                if(!$file_exists){  
                    $count_insert=0;
                    $count_update=0;

                    //Truncate Table
                    DB::table('expense')->where('type', 1)->delete();
                    
                    
                    $data = Excel::load($file)->toArray();
                    
                    foreach ($data as $row) {
                        $data_value=$row;

                        $str = $data_value['glpostingdate'];
                        $date = DateTime::createFromFormat('m/d/Y', $str);

                        if (Expense::where('expense_type', '=', $data_value['expensetype'])->where('job_number', '=', $data_value['jobnumber'])->where('amount', '=', $data_value['amount'])->where('account_number', '=', $data_value['glaccountnumber'])->where('posting_date', '=', $date->format('Y-m-d'))->count() > 0) {
                             //Insert Expense
                            if($data_value['amount']>0){
                                Expense::create([
                                    'expense_type'      => $data_value['expensetype'],    
                                    'job_number'        => $data_value['jobnumber'], 
                                    'amount'            => $data_value['amount'], 
                                    'account_number'    => $data_value['glaccountnumber'], 
                                    'posting_date'      => $date->format('Y-m-d'),
                                    'status'            => 2
                                ]);
                                $count_update++;
                            }
                        }else{
                            //Insert Expense
                            if($data_value['amount']>0){
                                Expense::create([
                                    'expense_type'      => $data_value['expensetype'],    
                                    'job_number'        => $data_value['jobnumber'], 
                                    'amount'            => $data_value['amount'], 
                                    'account_number'    => $data_value['glaccountnumber'], 
                                    'posting_date'      => $date->format('Y-m-d'),
                                ]);

                                $count_insert++;
                            }

                        }     
                        
                    }

                    File_load::create([
                        'file_name'   => $file,
                        'type'        => 3,  
                        'count_insert'=>    $count_insert,
                        'count_update'=>    $count_update,
                         
                    ]);
                }
                    

            } 
        }elseif ($type==4) {
            $directory=$path_prefix."data/budget";    
            $files = File::allFiles($directory);

            foreach ($files as $file) {
                $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 4)
                                        ->exists();
                if(!$file_exists){
                    $count_insert=0;
                    $count_update=0;
                    Budget::truncate();
                    
                    $data = Excel::load($file)->toArray();
                    
                    foreach ($data as $row) {
                        
                            $data_value=$row;

                            $str = $data_value['budgetdate'];
                            $date = DateTime::createFromFormat('m/d/Y', $str);
                            //Insert Profile
                            Budget::create([
                                'job_number'     => $data_value['jobnumber'],    
                                'hours'          => $data_value['budgetedhours'], 
                                'date'           => $date->format('Y-m-d'), 
                            ]);   
                            $count_insert++;    
                    }

                    File_load::create([
                        'file_name'   => $file,
                        'type'        => 4,   
                        'count_insert'=>    $count_insert,
                        'count_update'=>    $count_update,
                    ]);
                }
            }

        }elseif ($type==8) {
            $directory=$path_prefix."data/labor_tax";
            $files = File::allFiles($directory);
            foreach ($files as $file) {
                $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 8)
                                        ->exists();
                if(!$file_exists){
                    $count_insert=0;
                    $count_update=0;    
                    Labor_tax::truncate();

                    $data = Excel::load($file)->toArray();
                    foreach ($data as $row) {
                            $data_value=$row;
                            $str = $data_value['budgetdate'];
                            $date = DateTime::createFromFormat('m/d/Y', $str);
                            if (Labor_tax::where('daily_budget_id', '=', $data_value['dailybudgetid'])->count() > 0) {
                                //Update item
                                $labor_tax= labor_tax::where('daily_budget_id', '=', $data_value['dailybudgetid'])->first();
                                $labor_tax->account_number          = $data_value['glaccountnumber'];
                                $labor_tax->job_number              = $data_value['jobnumber'];
                                $labor_tax->budget_hour           = $data_value['budgetedhours'];
                                $labor_tax->budget_amount           = $data_value['budgeteddollars'];
                                $labor_tax->fica                    = $data_value['fica']; 
                                $labor_tax->futa                    = $data_value['futa'];
                                $labor_tax->suta                    = $data_value['suta']; 
                                $labor_tax->workmans_compensation   = $data_value['workmanscompensation'];
                                $labor_tax->medicare                = $data_value['medicare'];        
                                $labor_tax->date                    = $date->format('Y-m-d');
                                $labor_tax->save();
                                $count_update++;
                            }else{
                                $labor_tax=Labor_tax::create([
                                    'account_number'        => $data_value['glaccountnumber'],
                                    'job_number'            => $data_value['jobnumber'],
                                    'daily_budget_id'       => $data_value['dailybudgetid'],
                                    'budget_hour'          => $data_value['budgetedhours'],
                                    'budget_amount'         => $data_value['budgeteddollars'],
                                    'fica'                  => $data_value['fica'],
                                    'futa'                  => $data_value['futa'],
                                    'suta'                  => $data_value['suta'],
                                    'workmans_compensation' => $data_value['workmanscompensation'],
                                    'medicare'              => $data_value['medicare'],
                                    'date'                  => $date->format('Y-m-d'), 
                                ]);    
                                $count_insert++;
                            }     
                    }

                    File_load::create([
                        'file_name'   => $file,
                        'type'        => 8,   
                        'count_insert'=> $count_insert,
                        'count_update'=> $count_update, 
                    ]);
                }
            }        
        }elseif ($type==9) {
            $directory=$path_prefix."data/budget_monthly";
            $files = File::allFiles($directory);
            foreach ($files as $file) {
                $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 9)
                                        ->exists();
                if(!$file_exists){
                    $count_insert=0;
                    $count_update=0;

                    Budget_monthly::truncate();
                    
                    $data = Excel::load($file)->toArray();
                    foreach ($data as $row) {
                            $data_value=$row;
                                $budget_monthly=Budget_monthly::create([
                                    'monthly_budget'        => $data_value['monthlybudget'],
                                    'account_number'        => $data_value['glaccountnumber'],
                                    'budget_type'           => $data_value['budgettype'],
                                    'job_number'            => $data_value['jobnumber'],
                                    'period1'               => $data_value['period1'],    
                                    'period2'               => $data_value['period2'],
                                    'period3'               => $data_value['period3'],
                                    'period4'               => $data_value['period4'],
                                    'period5'               => $data_value['period5'],
                                    'period6'               => $data_value['period6'],
                                    'period7'               => $data_value['period7'],
                                    'period8'               => $data_value['period8'],
                                    'period9'               => $data_value['period9'],
                                    'period10'              => $data_value['period10'],
                                    'period11'              => $data_value['period11'],
                                    'period12'              => $data_value['period12'],
                                    'fiscal_year'           => $data_value['fiscalyear'],
                                    'fs'                    => $data_value['financialstatement'],
                                    'jc'                    => $data_value['jobcostanalysis'],
                                ]);    
                                $count_insert++;
                    }

                    File_load::create([
                        'file_name'   => $file,
                        'type'        => 9,  
                        'count_insert'=> $count_insert,
                        'count_update'=> $count_update, 
                    ]);
                }
            }        
        }elseif ($type==11) {
            $directory=$path_prefix."data/user_inactive";
            
            $files = File::allFiles($directory);
               
            foreach ($files as $file) {
                $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 11)
                                        ->exists();

                if(!$file_exists){
                    $count_insert=0; 
                    $count_update=0; 
                    $data = Excel::load($file)->toArray();
                    foreach ($data as $row) {
                        $data_value=$row;
                        //echo $data_value['employeenumber']."<br/>";
                        $user=User::where('employee_number', $data_value['employeenumber'])->where('active', '1')->first();
                        if(!is_null($user)){
                            $user->active=0;
                            $user->save();
                            $count_update++;    
                        }
                    }
                    File_load::create([
                        'file_name'   => $file,
                        'type'        => 11,   
                        'count_insert'=> $count_insert,
                        'count_update'=> $count_update, 
                    ]);
                }
            }  
        }elseif ($type==12) {
            $directory=$path_prefix."data/user_active";
            
            $files = File::allFiles($directory);
               
            foreach ($files as $file) {
                $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 12)
                                        ->exists();

                if(!$file_exists){
                    $count_insert=0; 
                    $count_update=0; 
                    $data = Excel::load($file)->toArray();
                    foreach ($data as $row) {
                        $data_value=$row;
                        $user=User::where('employee_number', $data_value['employeenumber'])->first();
                        if(!is_null($user)){
                            $user->manager_parent=$data_value['managerid'];
                            $user->area_supervisor_parent=$data_value['areasupervisorid'];
                            $user->supervisor_parent=$data_value['jobsupervisorid'];
                            $user->primary_job=$data_value['primaryjob'];
                            $user->classification_id=$data_value['classificationid'];
                            $user->supervisor_id=$data_value['supervisorid'];
                            $user->email_personal=((isset($data_value['emailaddress']))?$data_value['emailaddress']:'');
                            $user->type_id=$data_value['typeid'];
                            $user->active=1;
                            //Verify Role
                            if($user->role==9 || $user->role==8 || $user->role==5 || $user->role==6){
                                //Default Value
                                $role=9;
                                //Track user role
                                switch ($data_value['classificationid']) {
                                    case '11':
                                        $role=6;
                                        break;
                                    case '6':
                                        $role=5;
                                        break;
                                    case '3':
                                        $role=8;
                                        break;
                                    case '8':
                                        $role=8;
                                        break;
                                }
                                $user->role=$role;
                            }

                            $user->save();
                            $count_update++;    
                        }else{
                            if($data_value['locationid']==1){
                                //Default Value
                                $role=9;

                                //Track user role
                                switch ($data_value['classificationid']) {
                                    case '11':
                                        $role=6;
                                        break;
                                    case '6':
                                        $role=5;
                                        break;
                                    case '3':
                                        $role=8;
                                        break;
                                    case '8':
                                        $role=8;
                                        break;
                                }

                                $user=User::create([
                                    'first_name'        => ucfirst($data_value['firstname']),
                                    'middle_name'       => "",
                                    'last_name'         => ucfirst($data_value['lastname']),
                                    'email'             => ucfirst($data_value['firstname']).".".ucfirst($data_value['lastname'])."-".$data_value['employeenumber']."@encompassonsite.com",
                                    'role'              => $role,
                                    'employee_number'   => $data_value['employeenumber'],
                                    'manager_parent'    => ((isset($data_value['managerid']))?$data_value['managerid']:''),
                                    'area_supervisor_parent'    => ((isset($data_value['areasupervisorid']))?$data_value['areasupervisorid']:''),
                                    'supervisor_parent' => ((isset($data_value['jobsupervisorid']))?$data_value['jobsupervisorid']:''),
                                    'manager_id'        => 0,
                                    'password'          => bcrypt("encompass123"),
                                    'primary_job'       => (int)$data_value['primaryjob'],
                                    'classification_id' => ((isset($data_value['classificationid']))?$data_value['classificationid']:''), 
                                    'supervisor_id'     => ((isset($data_value['supervisorid']))?$data_value['supervisorid']:''), 
                                    'email_personal'    => ((isset($data_value['emailaddress']))?$data_value['emailaddress']:''),
                                    'type_id'           => ((isset($data_value['typeid']))?$data_value['typeid']:'')
                                ]);

                                //Insert Profile
                                UserProfile::create([
                                    'user_id'       => $user->id,
                                    'note'          => "User",
                                ]);
                                $count_insert++; 
                            }
                        }
                    }
                    File_load::create([
                        'file_name'   => $file,
                        'type'        => 12,   
                        'count_insert'=> $count_insert,
                        'count_update'=> $count_update, 
                    ]);
                }
            }  
        }elseif ($type==13) {
            $directory=$path_prefix."data/manager";
            
            $files = File::allFiles($directory);
               
            foreach ($files as $file) {
                $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 13)
                                        ->exists();

                if(!$file_exists){
                    //Clear manager ids
                    DB::table('users')->update(['manager_id' => 0]);

                    $count_insert=0; 
                    $count_update=0; 
                    $data = Excel::load($file)->toArray();

                    foreach ($data as $row) {
                        $data_value=$row;
                        if($data_value['employeenumber']!=""){
                            DB::table('users')->where('employee_number', $data_value['employeenumber'])->update(['manager_id' => $data_value['id']]);                            
                            $count_update++; 
                        }
                    }

                    File_load::create([
                        'file_name'   => $file,
                        'type'        => 13,   
                        'count_insert'=> $count_insert,
                        'count_update'=> $count_update, 
                    ]);

                }
            }    
            
        }elseif ($type==14) {
            $directory=$path_prefix."data/area_supervisor";
            
            $files = File::allFiles($directory);
               
            foreach ($files as $file) {
                $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 14)
                                        ->exists();

                if(!$file_exists){
                    //Clear supervisors ids
                    DB::table('users')->update(['area_supervisor_id' => 0]);

                    $count_insert=0; 
                    $count_update=0; 
                    $data = Excel::load($file)->toArray();
                    
                    foreach ($data as $row) {
                        $data_value=$row;
                        if($data_value['employeenumber']!=""){
                            DB::table('users')->where('employee_number', $data_value['employeenumber'])->update(['area_supervisor_id' => $data_value['id']]);                            
                            $count_update++; 
                        }
                    }
                    File_load::create([
                        'file_name'   => $file,
                        'type'        => 14,   
                        'count_insert'=> $count_insert,
                        'count_update'=> $count_update, 
                    ]);

                }
            }    
            
        }elseif ($type==15) {
            $directory=$path_prefix."data/supervisor";
            
            $files = File::allFiles($directory);
               
            foreach ($files as $file) {
                $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 15)
                                        ->exists();

                if(!$file_exists){
                    //Clear supervisors ids
                    DB::table('users')->update(['job_supervisor_id' => 0]);

                    $count_insert=0; 
                    $count_update=0; 
                    $data = Excel::load($file)->toArray();
                    foreach ($data as $row) {
                        $data_value=$row;
                        if($data_value['employeenumber']!=""){
                            DB::table('users')->where('employee_number', $data_value['employeenumber'])->update(['job_supervisor_id' => $data_value['id']]);                            
                            $count_update++; 
                        }
                    }

                    File_load::create([
                        'file_name'   => $file,
                        'type'        => 15,   
                        'count_insert'=> $count_insert,
                        'count_update'=> $count_update, 
                    ]);

                }
            }    
            
        }elseif ($type==19) {
            $directory=$path_prefix."data/timekeeping_data";
            $files = File::allFiles($directory);

            foreach ($files as $file) {
                $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 19)
                                        ->exists();
                if(!$file_exists){
                    $count_insert=0;
                    $count_update=0;
                    Timekeeping_data::truncate();
                    
                    $data = Excel::load($file)->toArray();
                    
                    foreach ($data as $row) {
                        
                            $data_value=$row;

                            //Insert Profile
                            Timekeeping_data::create([
                                'job_number'            => $data_value['jobnumber'],    
                                'hours'                 => $data_value['hours'], 
                                'lunch'                 => $data_value['lunch'],
                                'total'                 => $data_value['total'],
                                'period'            => $data_value['period'],    
                            ]);   
                            $count_insert++;    
                    }

                    File_load::create([
                        'file_name'   => $file,
                        'type'        => 19,   
                        'count_insert'=>    $count_insert,
                        'count_update'=>    $count_update,
                    ]);
                }
            }        

        }elseif ($type==20) {
            DB::table('expense')->where('type', 2)->delete();
            $client = new Client();
            $res = $client->request('GET', 'https://dirtpros.coupahost.com/api/invoices?status[in]=approved&exported=false', [
                'headers' => [
                    'X-COUPA-API-KEY' => 'e41f2e5bf5869e70760f5b3fd8e5b12b2629d3f2',
                    'ACCEPT'          => 'application/xml',
                ]
            ]);
            $code=$res->getStatusCode();    
            $header=$res->getHeader('content-type');
            $contents=$res->getBody();
            $string_xml=(string) $contents;
            
            $xml = simplexml_load_string($string_xml);


            foreach ($xml as $element) {
                $supplier_name=$element->{'supplier'}->{'name'};
                $vendor_number="";
                $account_number="";
                $last_invoice_id=$element->{'id'};

                $qVendor = DB::table('vendor')
                                        ->select(DB::raw('vendor_number, account_number'))
                                        ->where('name', strtoupper(trim($supplier_name)))
                                        ->get();

                    foreach ($qVendor as $value) {
                        $vendor_number=$value->vendor_number;         
                        $account_number=$value->account_number;
                    }

                if($vendor_number=="")
                    $vendor_number=$supplier_name;

                foreach ($element->{'invoice-lines'}->{'invoice-line'} as $item) {
                    $job_number_str=$item->{'account'}->{'segment-2'};
                    if($job_number_str!=""){
                        //Line with one account
                        $job_number_array = explode("-", $job_number_str);

                        Expense::create([
                            'expense_type'      => 'Supplies Coupa',    
                            'job_number'        => trim($job_number_array[0]), 
                            'amount'            => $item->{'accounting-total'}, 
                            'account_number'    => $account_number, 
                            'posting_date'      => $element->{'invoice-date'},
                            'type'              => 2 
                        ]);

                    }else{
                        //Multiple accounts
                        foreach ($item->{'account-allocations'} as $item_inner) {
                            foreach ($item_inner as $item_i) {
                                //echo "Amount:".($item_i->{'amount'})."<br/>";   
                                $job_number_str = $item_i->{'account'}->{'segment-2'};
                                $job_number_array = explode("-", $job_number_str);
                                Expense::create([
                                    'expense_type'      => 'Supplies Coupa',    
                                    'job_number'        => trim($job_number_array[0]), 
                                    'amount'            => $item_i->{'amount'}, 
                                    'account_number'    => $account_number, 
                                    'posting_date'      => $element->{'invoice-date'},
                                    'type'              => 2 
                                ]);
                            }   
                        }
                    }
                }
            }        
        }    
    }
}
