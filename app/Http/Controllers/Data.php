<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use File;

use App\Pay;
use App\Billable_hours;
use App\Job;
use App\Expense;
use App\Budget;
use App\Budget_data;
use App\Budget_gl;
use App\Budget_monthly;
use App\Actual_gl;
use App\Accounting_gl;
use App\Account;
use App\Report_account;
use App\Labor_tax;
use App\Quote_data;
use App\Quote_data_detail;
use App\Quote_category;
use App\User;
use App\UserProfile;
use App\File_load;
use App\Vendor;
use App\Configuration;
use Carbon\Carbon;

use App\Timekeeping;
use App\Timekeeping_data;
use App\Payroll_user;

use Excel;
use DateTime;



class Data extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        return view('data.home');
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processBillableHours()
    {
        $directory="data/billable_hours";
        set_time_limit(160);
        

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

        $result = File_load::where('type', 1)->paginate(100);        
        return view('data.process_billable_hours', ['files'  => $result]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processJob()
    {
        $directory="data/job";
        set_time_limit(160);
        

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
                            $job->region            = $data_value['tier1id'];
                            $job->country           = $data_value['tier2id']; 
                            $job->division          = $data_value['tier3id'];
                            $job->manager           = $data_value['tier5id']; 
                            $job->service           = $data_value['tier6id'];
                            $job->mayor_account     = $data_value['tier7id'];        
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
                            'region'            => $data_value['tier1id'], 
                            'country'           => $data_value['tier2id'], 
                            'division'          => $data_value['tier3id'],
                            'manager'           => $data_value['tier5id'], 
                            'service'           => $data_value['tier6id'],
                            'mayor_account'     => $data_value['tier7id'],        
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
                    'file_name'   => $file,
                    'type'        => 2,   
                    'count_insert'=>    $count_insert,
                    'count_update'=>    $count_update,      

                ]);
            }
                

        }
        $result = File_load::where('type', 2)->paginate(100);          
        return view('data.process_job', ['files'  => $result]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processSquareFeet()
    {
        $directory="data/square_feet";
        set_time_limit(160);
        

        $files = File::allFiles($directory);

        foreach ($files as $file) {
             $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 7)
                                    ->exists();
            if(!$file_exists){  
                
                File_load::create([
                    'file_name'   => $file,
                    'type'        => 7,   
                ]);
                
                $data = Excel::load($file)->toArray();
                
                foreach ($data as $row) {
                    $data_value=$row;
                    $job= Job::where('job_number', $data_value['jobnumber'])->first();
                    $job->square_feet=$data_value['usercodet_1'];
                    $job->save();
                    
                }
            }
                

        }
        $result = File_load::where('type', 2)->paginate(100);          
        return view('data.process_job', ['files'  => $result]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processExpense()
    {
        $directory="data/expense";
        set_time_limit(160);
        

        $files = File::allFiles($directory);

        foreach ($files as $file) {
             $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 3)
                                    ->exists();
            if(!$file_exists){  
                $count_insert=0;
                $count_update=0;

                //Truncate Table
                Expense::truncate();                
                
                $data = Excel::load($file)->toArray();
                
                foreach ($data as $row) {
                    $data_value=$row;

                    $str = $data_value['glpostingdate'];
                    $date = DateTime::createFromFormat('m/d/Y', $str);

                    if (Expense::where('expense_type', '=', $data_value['expensetype'])->where('job_number', '=', $data_value['jobnumber'])->where('amount', '=', $data_value['amount'])->where('account_number', '=', $data_value['glaccountnumber'])->where('posting_date', '=', $date->format('Y-m-d'))->count() > 0) {
                         //Insert Expense
                        Expense::create([
                            'expense_type'      => $data_value['expensetype'],    
                            'job_number'        => $data_value['jobnumber'], 
                            'amount'            => $data_value['amount'], 
                            'account_number'    => $data_value['glaccountnumber'], 
                            'posting_date'      => $date->format('Y-m-d'),
                            'status'            => 2
                        ]);
                        $count_update++;
                    }else{
                        //Insert Expense
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

                File_load::create([
                    'file_name'   => $file,
                    'type'        => 3,  
                    'count_insert'=>    $count_insert,
                    'count_update'=>    $count_update,
                     
                ]);
            }
                

        }  
        $result = File_load::where('type', 3)->paginate(100);        
        return view('data.process_expense', ['files'  => $result]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processBudget()
    {
        $directory="data/budget";
        set_time_limit(160);
        

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
        $result = File_load::where('type', 4)->paginate(100);        
        return view('data.process_budget', ['files'  => $result]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processBudgetData()
    {
        $directory="data/budget_data";
        set_time_limit(160);
        

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 18)
                                    ->exists();
            if(!$file_exists){
                $count_insert=0;
                $count_update=0;
                Budget_data::truncate();
                
                $data = Excel::load($file)->toArray();
                
                foreach ($data as $row) {
                    
                        $data_value=$row;

                        //Insert Profile
                        Budget_data::create([
                            'job_number'            => $data_value['jobnumber'],    
                            'budget_hours'          => $data_value['budgethours'], 
                            'budget_dollars'        => $data_value['budgetdollars'],
                            'fica'                  => $data_value['fica'],
                            'futa'                  => $data_value['futa'],
                            'suta'                  => $data_value['suta'],  
                            'workmans_compensation' => $data_value['workmanscompensation'],   
                            'medicare'              => $data_value['medicare'], 
                            'total'                 => ($data_value['budgetdollars']+$data_value['fica']+$data_value['futa']+$data_value['suta']+$data_value['workmanscompensation']+$data_value['medicare']),
                            'period'                => $data_value['period'],    
                        ]);   
                        $count_insert++;    
                }

                File_load::create([
                    'file_name'   => $file,
                    'type'        => 18,   
                    'count_insert'=>    $count_insert,
                    'count_update'=>    $count_update,
                ]);
            }
        }        

        return "Data Inserted";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processBudgetGL()
    {
        $directory="data/budget_gl";
        set_time_limit(160);
        

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 21)
                                    ->exists();
            if(!$file_exists){
                $count_insert=0;
                $count_update=0;
                Budget_gl::truncate();
                
                $data = Excel::load($file)->toArray();
                
                foreach ($data as $row) {
                    
                        $data_value=$row;

                        //Insert Profile
                        Budget_gl::create([
                            'job_number'         => $data_value['jobnumber'],  
                            'period1'            => $data_value['period1'],    
                            'period2'            => $data_value['period2'],
                            'period3'            => $data_value['period3'],
                            'period4'            => $data_value['period4'],
                            'period5'            => $data_value['period5'],
                            'period6'            => $data_value['period6'],
                            'period7'            => $data_value['period7'],
                            'period8'            => $data_value['period8'],
                            'period9'            => $data_value['period9'],
                            'period10'            => $data_value['period10'],
                            'period11'            => $data_value['period11'],
                            'period12'            => $data_value['period12'],
                        ]);   
                        $count_insert++;    
                }

                File_load::create([
                    'file_name'   => $file,
                    'type'        => 21,   
                    'count_insert'=> $count_insert,
                    'count_update'=> $count_update,
                ]);
            }
        }        

        return "Data Inserted";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processActualGL()
    {
        $directory="data/actual_gl";
        set_time_limit(160);
        

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 22)
                                    ->exists();
            if(!$file_exists){
                $count_insert=0;
                $count_update=0;
                Actual_gl::truncate();
                
                $data = Excel::load($file)->toArray();
                
                foreach ($data as $row) {
                    
                        $data_value=$row;

                        //Insert Profile
                        Actual_gl::create([
                            'job_number'         => $data_value['jobnumber'],  
                            'actual_total'       => $data_value['actual_total'],
                            'period'            => $data_value['period'],    
                        ]);   
                        $count_insert++;    
                }

                File_load::create([
                    'file_name'   => $file,
                    'type'        => 22,   
                    'count_insert'=> $count_insert,
                    'count_update'=> $count_update,
                ]);
            }
        }        

        return "Data Inserted GL";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processAccountingGL()
    {
        $directory="data/accounting_gl";
        set_time_limit(160);
        

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 26)
                                    ->exists();
            if(!$file_exists){
                $count_insert=0;
                $count_update=0;
                Accounting_gl::truncate();
                
                $data = Excel::load($file)->toArray();
                
                foreach ($data as $row) {
                    
                        $data_value=$row;

                        //Insert Profile
                        Accounting_gl::create([
                            'job_number'            => $data_value['jobnumber'],  
                            'total'                 => $data_value['actual_total'],
                            'period'                => $data_value['period'],
                            'level'                 => $data_value['totallevel'],    
                        ]);   
                        $count_insert++;    
                }

                File_load::create([
                    'file_name'   => $file,
                    'type'        => 26,   
                    'count_insert'=> $count_insert,
                    'count_update'=> $count_update,
                ]);
            }
        }        

        return "Data Inserted Accounting GL";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processTaskcodes()
    {
        $directory="data/taskcodes";
        set_time_limit(160);
        

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 23)
                                    ->exists();
            if(!$file_exists){
                $count_insert=0;
                $count_update=0;
                Quote_data::truncate();
                
                $data = Excel::load($file)->toArray();
            
                foreach ($data as $row) {
                        $data_value=$row;
                        
                        if($data_value['measuretypeid']==1){
                            $price=$data_value['price']/1000;
                            $minutes=$data_value['minutes'];
                        }else{
                            $price=$data_value['price'];
                            $minutes=$data_value['minutes']*1000;
                        }

                        Quote_data::create([
                            'task_id'               => $data_value['id'],  
                            'task_code'             => $data_value['taskcode'],
                            'measure_type'          => $data_value['measuretypeid'],
                            'data_subject'          => $data_value['taskname'],    
                            'minutes'               => $minutes,    
                            'category_type_id'      => $data_value['typeid'],
                            'price'                 => $price,
                            'base_price'            => $data_value['price'],    
                        ]);   
                        $count_insert++;    
                }

                File_load::create([
                    'file_name'   => $file,
                    'type'        => 23,   
                    'count_insert'=> $count_insert,
                    'count_update'=> $count_update,
                ]);
            }
        }        

        return "Data Inserted TaskCodes";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processTaskcodesDetail()
    {
        $directory="data/taskcodes_details";
        set_time_limit(160);
        

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 24)
                                    ->exists();
            if(!$file_exists){
                $count_insert=0;
                $count_update=0;
                Quote_data_detail::truncate();
                
                $data = Excel::load($file)->toArray();
                
                foreach ($data as $row) {
                    
                        $data_value=$row;

                        //Insert Profile
                        Quote_data_detail::create([
                            'task_id'               => $data_value['taskid'],
                            'description_id'        => $data_value['pemdescriptionid'],
                            'description'           => $data_value['description'],    
                            'cost'                  => $data_value['cost'],    
                            'active'                => $data_value['active']
                        ]);   
                        $count_insert++;    
                }

                File_load::create([
                    'file_name'   => $file,
                    'type'        => 24,   
                    'count_insert'=> $count_insert,
                    'count_update'=> $count_update,
                ]);
            }
        }        

        return "Data Inserted TaskCodes Detail";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processTaskcodesCategory()
    {
        $directory="data/taskcodes_category";
        set_time_limit(160);
        

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 25)
                                    ->exists();
            if(!$file_exists){
                $count_insert=0;
                $count_update=0;
                Quote_category::truncate();
                
                $data = Excel::load($file)->toArray();
                
                foreach ($data as $row) {
                    
                        $data_value=$row;

                        //Insert Profile
                        Quote_category::create([
                            'type_id'               => $data_value['id'],
                            'name'                  => $data_value['description'],
                            'type'                  => 1,    
                            'active'                => $data_value['active']
                        ]);   
                        $count_insert++;    
                }

                File_load::create([
                    'file_name'   => $file,
                    'type'        => 25,   
                    'count_insert'=> $count_insert,
                    'count_update'=> $count_update,
                ]);
            }
        }        

        return "Data Inserted TaskCodes Category";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processTimekeepingData()
    {
        $directory="data/timekeeping_data";
        set_time_limit(160);
        

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

        return "Data Inserted";
    }



    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processAccount()
    {
        $directory="data/account";
        set_time_limit(160);
        

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 5)
                                    ->exists();
            if(!$file_exists){
                $count_insert=0;
                $count_update=0;

                $data = Excel::load($file)->toArray();
                
                foreach ($data as $row) {
                    
                        $data_value=$row;

                        //Insert Profile
                        Account::create([
                            'account_number'             => $data_value['glaccountnumber'],    
                            'type_description'           => $data_value['accounttypedescription'], 
                            'description'                => $data_value['glaccountdescription'], 
                            'category_description'       => $data_value['categorydescription'], 
                            'category_type_description'  => $data_value['categorytypedescription'], 
                        ]);

                        $count_insert++;
                    
                }

                File_load::create([
                    'file_name'   => $file,
                    'type'        => 5, 
                    'count_insert'=> $count_insert,
                    'count_update'=> $count_update,  
                ]);
            }
                

        }        
        $result = File_load::where('type', 5)->paginate(100);        
        return view('data.process_account', ['files'  => $result]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processUser()
    {
        $directory="data/user";
        set_time_limit(160);
        

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 6)
                                    ->exists();
            if(!$file_exists){
                File_load::create([
                    'file_name'   => $file,
                    'type'        => 6,   
                ]);
                
                $data = Excel::load($file)->toArray();
                
                foreach ($data as $row) {
                    
                        $data_value=$row;
                        if(is_numeric ($data_value['supervisor']))
                            $supervisor=$data_value['supervisor'];
                        else
                            $supervisor=0;
                            
                        $user=User::create([
                            'first_name'        => ucfirst($data_value['firstname']),
                            'middle_name'       => "",
                            'last_name'         => ucfirst($data_value['lastname']),
                            'email'             => ucfirst($data_value['firstname']).".".ucfirst($data_value['lastname']).".".$data_value['employeenumber']."@encompassonsite.com",
                            'role'              => 9,
                            'employee_number'   => $data_value['employeenumber'],
                            'manager_parent'    => $supervisor,
                            'manager_id'        => 0,
                            'password'          => bcrypt("encompass123"),
                        ]);

                        //Insert Profile
                        UserProfile::create([
                            'user_id'       => $user->id,
                            'note'          => "User",
                        ]);


                    
                }
            }
        }        
        return "Data Inserted";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processUserInactive()
    {
        $directory="data/user_inactive";
        set_time_limit(160);
        

        $files = File::allFiles($directory);
        $count_insert=0; 
        $count_update=0; 
        foreach ($files as $file) {
            $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 11)
                                    ->exists();

            if(!$file_exists){
                
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
        return "Data User Inactive";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processUserActive()
    {
        $directory="data/user_active";
        set_time_limit(160);
        

        $files = File::allFiles($directory);
        $count_insert=0; 
        $count_update=0; 
        foreach ($files as $file) {
            $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 12)
                                    ->exists();

            if(!$file_exists){
                
                $data = Excel::load($file)->toArray();
                
                foreach ($data as $row) {
                    $data_value=$row;
                    //echo $data_value['employeenumber']."<br/>";
                    $user=User::where('employee_number', $data_value['employeenumber'])->where('active', '1')->first();
                    if(is_null($user)){
                        echo $data_value['employeenumber']."<br/>";
                        $count_insert++;    
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
        return "Data User Active";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processLaborTax()
    {
        $directory="data/labor_tax";
        set_time_limit(160);
        

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
                                'budget_hour'           => $data_value['budgetedhours'],
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
        return "Data Inserted";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processBudgetMonthly()
    {
        $directory="data/budget_monthly";
        set_time_limit(160);
        

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
        return "Data Inserted";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processVendor()
    {
        $directory="data/vendor";
        set_time_limit(160);
        

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 10)
                                    ->exists();
            if(!$file_exists){
                $count_insert=0;
                $count_update=0;
                
                $data = Excel::load($file)->toArray();
                foreach ($data as $row) {
                    
                        $data_value=$row;
                        if (Vendor::where('vendor_number', '=', $data_value['vendornumber'])->count() > 0) {
                            $vendor= Vendor::where('vendor_number', '=', $data_value['vendornumber'])->first();
                            $vendor->name = $data_value['vendorname'];
                            $budget_monthly->save();
                            $count_update++;
                        }else{
                            $vendor=Vendor::create([
                                'vendor_number'         => $data_value['vendornumber'],
                                'name'                  => $data_value['vendorname'],
                                'account_number'        => ""
                            ]);    
                            $count_insert++;
                        }    
                        
                    
                }

                File_load::create([
                    'file_name'   => $file,
                    'type'        => 10,  
                    'count_insert'=> $count_insert,
                    'count_update'=> $count_update, 
                ]);
            }
        }        
        return "Data Inserted";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processTimekeeping()
    {
        $directory="wotc_tk";
        set_time_limit(160);
        

        $files = File::allFiles($directory);
            $dateNow = Carbon::now();
            $IniDate = $dateNow->copy()->subWeek(4);

            $date_ini_data=Configuration::where('variable', 'PAYROLL_DATE_INI')->first();
            $date_end_data=Configuration::where('variable', 'PAYROLL_DATE_END')->first();

            $date_ini_data->value=$IniDate->format('Y-m-d');
            $date_end_data->value=date('Y-m-d');

            $date_ini_data->save();
            $date_end_data->save();

            foreach ($files as $file) {

                
                $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 16)
                                        ->exists();
                if(!$file_exists){                        
                    $count_insert=0;
                    $count_update=0;
                    Timekeeping::truncate();

                    $data = Excel::load($file)->toArray();
                    echo $file;
                    foreach ($data as $row) {
                        
                        $data_value=$row;
                        
                        $str = $data_value['workdate2'];
                        $date = DateTime::createFromFormat('m/d/Y', $str);
                            Timekeeping::create([
                                'winteam_id'        => $data_value['id'], 
                                'job_number'        => $data_value['jobnumber'], 
                                'employee_number'   => $data_value['employeenumber'],
                                'work_date2'         => $date->format('Y-m-d'), 
                                'hours'             => isset($data_value['hours'])?$data_value['hours']:"",        
                                'lunch'             => isset($data_value['lunch'])?$data_value['lunch']:"",
                                'pay_rate'          => isset($data_value['payrate'])?$data_value['payrate']:"",        
                            ]);
                            $count_insert++;
                        
                        
                    }

                    //Insert file record
                    File_load::create([
                        'file_name'   =>    $file,
                        'type'        =>    16, 
                        'count_insert'=>    $count_insert,
                        'count_update'=>    $count_update,      
                    ]);


                }
                    

            }        
        return "Data Inserted";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processWOTC()
    {
        $directory="wotc";
        set_time_limit(160);
        
        $files = File::allFiles($directory);

            foreach ($files as $file) {
                 $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 17)
                                        ->exists();
                if(!$file_exists){  
                    $count_insert=0;
                    $count_update=0;
                    Payroll_user::truncate();

                    $data = Excel::load($file)->toArray();
                    
                    foreach ($data as $row) {

                            $data_value=$row;

                            $str = $data_value['birthdate'];
                            $date_birthdate = DateTime::createFromFormat('m/d/Y', $str);

                            $str = $data_value['hiredate'];
                            $date_hiredate = DateTime::createFromFormat('m/d/Y', $str);


                            Payroll_user::create([
                                'ssn'                => $data_value['socialsecuritynumber'], 
                                'employee_number'    => $data_value['employeenumber'],
                                'birth_date'         => $date_birthdate->format('Y-m-d'), 
                                'first_name'         => $data_value['firstname'],        
                                'last_name'          => $data_value['lastname'],
                                'address'            => $data_value['address1'],        
                                'city'               => $data_value['city'],        
                                'state'              => $data_value['state'],        
                                'postal_code'        => $data_value['zip'],        
                                'hire_date'          => $date_hiredate->format('Y-m-d'),        
                                'type'               => $data_value['typeid'],        
                                'rate'               => $data_value['payrate'], 
                                'job_title'          => $data_value['jobtitle']
                            ]);

                            $count_insert++;
                                     
                    }

                    //Insert file
                    File_load::create([
                        'file_name'   =>    $file,
                        'type'        =>    17,   
                        'count_insert'=>    $count_insert,
                        'count_update'=>    $count_update,      

                    ]);
                }
            }

            
        return "Data Inserted";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function processReportAccount()
    {
        $directory="data/report_account";
        set_time_limit(160);
        

        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $file_exists=File_load::where('file_name', $file)
                                    ->where('type', 27)
                                    ->exists();
            if(!$file_exists){
                $count_insert=0;
                $count_update=0;

                $data = Excel::load($file)->toArray();
                
                foreach ($data as $row) {
                    
                        $data_value=$row;

                        //Insert
                        Report_account::create([
                            'account_number'             => $data_value['glaccountnumber'],    
                            'level'                      => $data_value['totallevel'], 
                            'type'                       => $data_value['type'], 
                            'format_id'                  => $data_value['formatid']
                        ]);

                        $count_insert++;
                    
                }

                File_load::create([
                    'file_name'   => $file,
                    'type'        => 27, 
                    'count_insert'=> $count_insert,
                    'count_update'=> $count_update,  
                ]);
            }
                

        }        
        return "Data Inserted Account Report";
    }



    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function viewBillableHours()
    {
        $result = Billable_hours::paginate(50);
        return view('data.list_billable_hours', ['billable_hours'  => $result]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function viewJob()
    {
        $result = Job::paginate(50);
        return view('data.list_job', ['job'  => $result]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function viewExpense()
    {
        $result = Expense::paginate(50);
        return view('data.list_expense', ['expense'  => $result]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function viewBudget()
    {
        $result = Budget::paginate(50);
        return view('data.list_budget', ['budget'  => $result]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function viewAccount()
    {
        $result = Account::orderBy('financial', 'desc')->paginate(50);
        return view('data.list_account', ['account'  => $result]);
    }

}
