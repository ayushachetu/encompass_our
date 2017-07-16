<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use File;
use Excel;
use DateTime;

use App\Budget_data;
use App\Budget_gl;
use App\Budget_monthly;
use App\Actual_gl;
use App\Accounting_gl;
use App\Schedule_task;
use App\Report_account;
use App\Configuration;
use Carbon\Carbon;
use App\File_load;

class BudgetSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'budgetsync {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync budget&actual information from WINTEAM';

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
        if($type==18){
            $directory=$path_prefix."data/budget_data";
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
            
        }elseif($type==21){
            $directory=$path_prefix."data/budget_gl";
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
                                'period'             => $data_value['period'],  
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

            
        }elseif($type==22){
            $directory=$path_prefix."data/actual_gl";
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
            
        }elseif($type==26){
            $directory=$path_prefix."data/accounting_gl";
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
        }elseif($type==27){
            $directory=$path_prefix."data/report_account";
            $files = File::allFiles($directory);

            foreach ($files as $file) {
                $file_exists=File_load::where('file_name', $file)
                                        ->where('type', 27)
                                        ->exists();
                if(!$file_exists){
                    $count_insert=0;
                    $count_update=0;
                    Report_account::truncate();
                    
                    $data = Excel::load($file)->toArray();
                    
                    foreach ($data as $row) {
                        
                            $data_value=$row;

                             //Report
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
        }   
    }
}
