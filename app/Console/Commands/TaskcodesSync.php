<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use File;
use Excel;
use DateTime;

use App\Schedule_task;
use App\Quote_data;
use App\Quote_data_detail;
use App\Quote_category;
use App\Configuration;
use Carbon\Carbon;
use App\File_load;

class TaskcodesSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taskcodessync {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync taskcodes information from WINTEAM';

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
        if($type==23){
            $directory=$path_prefix."data/taskcodes";
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
            
        }elseif($type==24){
            $directory=$path_prefix."data/taskcodes_details";

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

            
        }elseif($type==25){
            $directory=$path_prefix."data/taskcodes_category";
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
                            if($data_value['active']==1){
                                //Insert Category
                                Quote_category::create([
                                    'type_id'               => $data_value['id'],
                                    'name'                  => $data_value['description'],
                                    'type'                  => 1,    
                                    'active'                => $data_value['active']
                                ]);   
                                $count_insert++;    
                            }
                    }

                    File_load::create([
                        'file_name'   => $file,
                        'type'        => 25,   
                        'count_insert'=> $count_insert,
                        'count_update'=> $count_update,
                    ]);
                }
            }    
            
        }   
    }
}
