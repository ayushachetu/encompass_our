<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use DateTime;

use App\Schedule_task;
use App\File_load;
use Carbon\Carbon;

use App\Deal;
use App\Deal_pipeline;
use App\Deal_stage;

use GuzzleHttp\Client;


class DealSync extends Command
{ 
    protected $hapikey='8c51a2a3-796b-4d9e-bf35-e4f7e94c165e';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dealsync {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync deal information from HUBSPOT';

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
        
        $schedule_task=Schedule_task::create([
            'name'         => 'task'.strtotime("now"),
            'number'       => 80,
            'type'         => $type  
        ]);

        //Job Sync
        if($type==50){
            $count=0;
            $ptr=0;
            $has_more=true;

            $client = new Client();
            Deal::truncate();        
            while ($has_more) {
                $res = $client->request('GET', 'https://api.hubapi.com/deals/v1/deal/paged?hapikey='.$this->hapikey.'&includeAssociations=true&limit=100&properties=dealname&properties=hubspot_owner_id&properties=amount&properties=dealstage&properties=createdate&properties=closedate&properties=account_manager&properties=deal_vertical&offset='.$ptr);
                $code=$res->getStatusCode();    
                $contents=$res->getBody();
                $content_array = json_decode($contents, true);

                foreach ($content_array['deals'] as $value) {
                    $prop=$value['properties'];

                    if(isset($prop['closedate']['value']))
                        $close_date=date('Y-m-d H:i:s',  substr($prop['closedate']['value'], 0, 10));
                    else
                        $close_date='0000-00-00 00:00:00';

                    $deal_stage=(isset($prop['dealstage']['value'])?$prop['dealstage']['value']:'');

                    if($deal_stage!=""){
                        $element_stage=Deal_stage::where('stage_id', $deal_stage)->first();
                        $pipeline_id=$element_stage->pipeline_id;
                    }else{
                        $pipeline_id="";
                    }

                    $objDeal=Deal::create([
                        'deal_id'           =>  $value['dealId'],
                        'name'              =>  $prop['dealname']['value'],
                        'hubspot_owner_id'  =>  (isset($prop['hubspot_owner_id']['value'])?$prop['hubspot_owner_id']['value']:0),
                        'amount'            =>  (isset($prop['amount']['value'])?$prop['amount']['value']:0),
                        'deal_stage'        =>  $deal_stage,
                        'pipeline_id'       =>  $pipeline_id,
                        'create_date'       =>  date('Y-m-d H:i:s', substr($prop['createdate']['value'], 0, 10)),
                        'close_date'        =>  $close_date,
                        'account_manager'   =>  (isset($prop['account_manager']['value'])?$prop['account_manager']['value']:''),
                        'deal_vertical'     =>  (isset($prop['deal_vertical']['value'])?$prop['deal_vertical']['value']:'')
                    ]);      
                }
                

                $has_more=$content_array['hasMore'];
                if($has_more){
                   $ptr=$content_array['offset']; 
                }

            }
            
        }elseif($type==51){
            $client = new Client();
            $res = $client->request('GET', 'https://api.hubapi.com/deals/v1/pipelines?hapikey='.$this->hapikey);
            $code=$res->getStatusCode();    
            $header=$res->getHeader('content-type');
            $contents=$res->getBody();

            Deal_pipeline::truncate();
            Deal_stage::truncate();
            $content_array = json_decode($contents, true);
            
            foreach ($content_array as $value) {
                $objDealPipeline=Deal_pipeline::create([
                    'pipeline_id'       =>  $value['pipelineId'],
                    'label'             =>  $value['label'],
                    'active'            =>  $value['active'],
                    'display_order'     =>  $value['displayOrder'],
                ]); 
                foreach ($value['stages'] as $stage) {
                   Deal_stage::create([
                    'pipeline_id'       =>  $value['pipelineId'],
                    'stage_id'          =>  $stage['stageId'],
                    'label'             =>  $stage['label'],
                    'probability'       =>  $stage['probability'],        
                    'active'            =>  $stage['active'],
                    'display_order'     =>  $stage['displayOrder'],
                    'close_won'         =>  $stage['closedWon']
                    ]);
                }
            }
        }   
    }
}