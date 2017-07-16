<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use File;
use Excel;
use DateTime;

use App\Schedule_task;
use App\Timekeeping;
use App\Payroll_user;
use App\Payroll_paycheck;
use App\Configuration;
use Carbon\Carbon;
use App\File_load;

class PayrollSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payrollsync {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync payroll information from WINTEAM';

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
        if($type==16){
            $directory=$path_prefix."wotc_tk";
            $files = File::allFiles($directory);
            $dateNow = Carbon::now();
            $IniDate = $dateNow->copy()->subDays(60);

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
                    
                    foreach ($data as $row) {
                    
                        $data_value=$row;
                        
                        $str = $data_value['workdate'];
                        $date = DateTime::createFromFormat('m/d/Y', $str);
                            Timekeeping::create([
                                'winteam_id'        => $data_value['id'], 
                                'job_number'        => $data_value['jobnumber'], 
                                'employee_number'   => $data_value['employeenumber'],
                                'work_date'         => $date->format('Y-m-d'), 
                                'hours'             => isset($data_value['hours'])?$data_value['hours']:"",        
                                'lunch'             => isset($data_value['lunch'])?$data_value['lunch']:"",
                                'overtimehours'     => isset($data_value['overtimehours'])?$data_value['overtimehours']:"",
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
        }elseif($type==17){
            $directory=$path_prefix."wotc";

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
                                'job_title'          => $data_value['jobtitle'],
                                'part_full'          => $data_value['ftpt'],
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
        }elseif($type==30){
            $directory=$path_prefix."wotc_paycheck";

            $files = File::allFiles($directory);

            foreach ($files as $file) {
                 $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 30)
                                        ->exists();
                if(!$file_exists){  

                     

                    $count_insert=0;
                    $count_update=0;
                    Payroll_paycheck::truncate();


                    $data = Excel::load($file)->toArray();
                    
                    foreach ($data as $row) {

                            $data_value=$row;

                            $str = $data_value['checkdate'];
                            $date_check = DateTime::createFromFormat('m/d/Y', $str);

                            Payroll_paycheck::create([
                                'employee_number'    => $data_value['employeenumber'],
                                'check_number'       => $data_value['checknumber'],        
                                'check_date'         => $date_check->format('Y-m-d'), 
                                'total'              => $data_value['totalwages'],        
                            ]);

                            $count_insert++;
                                     
                    }

                    //Insert file
                    File_load::create([
                        'file_name'   =>    $file,
                        'type'        =>    30,   
                        'count_insert'=>    $count_insert,
                        'count_update'=>    $count_update,      

                    ]);
                }
            }
        }   
    }
}
