<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use DB;
use Auth;
use Config;
use Response;
use Carbon\Carbon;
use App\Job;



class MapsController extends Controller
{    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex($industry=0)
    {
        if($industry==0){
            $job_list_active=Job::where('active', 1)->orderBy('job_number', 'asc')->get();
            $job_list_pending=Job::where('active', 1)->where('latitude', 0)->where('longitude', 0)->orderBy('job_number', 'asc')->get();
        }else{
            $job_list_active=Job::where('active', 1)->where('division', $industry)->orderBy('job_number', 'asc')->get();
            $job_list_pending=Job::where('active', 1)->where('division', $industry)->where('latitude', 0)->where('longitude', 0)->orderBy('job_number', 'asc')->get();
        }

        return view('maps.page',
        	['job_list'                 =>  $job_list_active,
            'job_list_pending'          =>  $job_list_pending,
            'list_industry'             =>  $this->listIndustry(),
            'list_industry_icon'        =>  $this->listIndustryIcons(),
            'industry'                  =>  $industry
            ] 
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

    private function listIndustryIcons()
    {
       $path='https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_'; 
      return array(
            '1'   =>  $path.'red.png',
            '3'   =>  $path.'black.png',
            '4'   =>  $path.'blue.png',
            '5'   =>  $path.'green.png',
            '6'   =>  $path.'grey.png',
            '7'   =>  $path.'orange.png',
            '8'   =>  $path.'purple.png',
            '9'   =>  $path.'white.png',
            '10'  =>  $path.'yellow.png',
        );
    }

}
