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
use App\Evaluation;



class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
    	$dateEvaluate = Carbon::now();
    	$role_user=Auth::user()->getRole();
      $manager_id=0;


    	if($role_user==Config::get('roles.AREA_MANAGER')){
            //Get Manager ID 
    		    $manager_id=Auth::user()->getManagerId();
    		 
            //Get user that have not been reviewed
            $users = DB::table('users')
                           ->select(DB::raw('users.*')) 
                           ->where('manager_parent', $manager_id)
                           ->where('active', 1)
                           ->whereExists(function ($query) {
                                $query->select(DB::raw(1))
                                      ->from('evaluation')
                                      ->where('evaluation.user_id', Auth::user()->id)
                                      ->whereRaw('evaluation.evaluate_user_id = users.id');
                            }, " AND NOT ")
                          ->get();

            //Get users that have been reviewed
            $users_eval = DB::table('users')
                            ->select(DB::raw('users.*, (SELECT evaluation.created_at FROM evaluation WHERE evaluation.evaluate_user_id=users.id ORDER BY evaluation.created_at DESC limit 1)  as "e_created_at"')) 
                            ->whereExists(function ($query) {
                                $query->select(DB::raw(1))
                                      ->from('evaluation')
                                      ->where('evaluation.user_id',   Auth::user()->id)
                                      ->whereRaw('evaluation.evaluate_user_id = users.id');
                            })
                            ->where('manager_parent', $manager_id)
                            ->where('active', 1)
                            ->orderBy('e_created_at', 'asc')
                            ->get();
        }elseif($role_user==Config::get('roles.DIR_POS')){
        	  $users = DB::table('users')
                             ->where(function ($query) use ($role_user) {
                                  $query->where('users.manager_id', '!=',  0)
                                        ->orWhere(function ($query2) use ($role_user) {
                                             $query2->where('users.role','<>' , Config::get('roles.ADMIN'))
                                                    ->where('users.id', '<>', Auth::user()->id);
                                  });
                              })
                             ->whereExists(function ($query) {
                                $query->select(DB::raw(1))
                                      ->from('evaluation')
                                      ->where('evaluation.user_id', Auth::user()->id)
                                      ->whereRaw('evaluation.evaluate_user_id = users.id');
                              }, " AND NOT ")
                            ->where('active', 1)
                            ->get();

            $users_eval = DB::table('users')
                            ->select(DB::raw('users.*, evaluation.created_at  as "e_created_at"')) 
                            ->join('evaluation', 'users.id', '=', 'evaluation.evaluate_user_id')
                            ->where('evaluation.user_id',   Auth::user()->id)
                            ->where(function ($query) use ($role_user) {
                                  $query->where('users.manager_id', '!=',  0)
                                        ->orWhere(function ($query2) use ($role_user) {
                                             $query2->where('users.role','<>' , Config::get('roles.ADMIN'))
                                                    ->where('users.id', '<>', Auth::user()->id);
                                  });
                              })
                            ->where('active', 1)
                            ->orderBy('evaluation.created_at', 'asc')
                            ->get();
        }elseif($role_user==Config::get('roles.AREA_SUPERVISOR')){
            //Get Manager ID 
            
            $primary_job=Auth::user()->gePrimayJob();

            $job_query     = DB::table('job')
                            ->where('job_number', $primary_job)
                            ->get();

            foreach ($job_query as $value) {
                $manager_id=$value->manager;         
            } 
         
            //Get user that have not been reviewed
            $users = DB::table('users')
                           ->select(DB::raw('users.*')) 
                           ->where('manager_parent', $manager_id)
                           ->where('id', '<>' , Auth::user()->id)
                           ->whereExists(function ($query) {
                                $query->select(DB::raw(1))
                                      ->from('evaluation')
                                      ->where('evaluation.user_id', Auth::user()->id)
                                      ->whereRaw('evaluation.evaluate_user_id = users.id');
                            }, " AND NOT ")
                           ->where('active', 1)
                          ->get();

            //Get users that have been reviewed
            $users_eval = DB::table('users')
                            ->select(DB::raw('users.*, (SELECT evaluation.created_at FROM evaluation WHERE evaluation.evaluate_user_id=users.id ORDER BY evaluation.created_at DESC limit 1)  as "e_created_at"')) 
                            ->whereExists(function ($query) {
                                $query->select(DB::raw(1))
                                      ->from('evaluation')
                                      ->where('evaluation.user_id',   Auth::user()->id)
                                      ->whereRaw('evaluation.evaluate_user_id = users.id');
                            })
                            ->where('manager_parent', $manager_id)
                            ->where('id', '<>' , Auth::user()->id)
                            ->where('active', 1)
                            ->orderBy('e_created_at', 'asc')
                            ->get();
        }elseif($role_user==Config::get('roles.SUPERVISOR')){
            //Get Manager ID 
            
            $primary_job=Auth::user()->gePrimayJob();

            $job_query     = DB::table('job')
                            ->where('job_number', $primary_job)
                            ->get();

            foreach ($job_query as $value) {
                $manager_id=$value->manager;         
            } 
         
            //Get user that have not been reviewed
            $users = DB::table('users')
                           ->select(DB::raw('users.*')) 
                           ->where('manager_parent', $manager_id)
                           ->where('id', '<>' , Auth::user()->id)
                           ->whereExists(function ($query) {
                                $query->select(DB::raw(1))
                                      ->from('evaluation')
                                      ->where('evaluation.user_id', Auth::user()->id)
                                      ->whereRaw('evaluation.evaluate_user_id = users.id');
                            }, " AND NOT ")
                           ->where('active', 1)
                          ->get();

            //Get users that have been reviewed
            $users_eval = DB::table('users')
                            ->select(DB::raw('users.*, (SELECT evaluation.created_at FROM evaluation WHERE evaluation.evaluate_user_id=users.id ORDER BY evaluation.created_at DESC limit 1)  as "e_created_at"')) 
                            ->whereExists(function ($query) {
                                $query->select(DB::raw(1))
                                      ->from('evaluation')
                                      ->where('evaluation.user_id',   Auth::user()->id)
                                      ->whereRaw('evaluation.evaluate_user_id = users.id');
                            })
                            ->where('manager_parent', $manager_id)
                            ->where('id', '<>' , Auth::user()->id)
                            ->where('active', 1)
                            ->orderBy('e_created_at', 'asc')
                            ->get();
        }else{  
          $users = DB::table('users')
                            ->where('manager_id', '!=',  0)
                            ->where('active', 1)
                            ->get();

            $users_eval = DB::table('users')
                            ->select(DB::raw('users.*, evaluation.created_at  as "e_created_at"')) 
                            ->join('evaluation', 'users.id', '=', 'evaluation.evaluate_user_id')
                            ->where('manager_id', '!=',  0)
                            ->where('evaluation.user_id',   Auth::user()->id)
                            ->where('active', 1)
                            ->orderBy('evaluation.created_at', 'asc')
                            ->get();

        }

        return view('evaluation.page',
        				['users'        => $users,
                 'users_eval'   => $users_eval,
                 'manager_id'   => $manager_id,
        				 'dateEvaluate'	=> $dateEvaluate->format('F Y')	]
        				);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function getHistory()
    {
        $dateEvaluate = Carbon::now();

        $role_user=Auth::user()->getRole();


        if($role_user==Config::get('roles.AREA_MANAGER')){
            $manager_id=Auth::user()->getManagerId();
             
             $users = DB::table('users')
                            ->join('evaluation', 'users.id', '=', 'evaluation.evaluate_user_id')
                            ->where('manager_parent', $manager_id)
                            ->where('evaluation.user_id',   Auth::user()->id)
                            ->where('active', 1)
                            ->orderBy('evaluation.created_at', 'desc')
                            ->get();
        }elseif($role_user==Config::get('roles.DIR_POS')){
            $users = DB::table('users')
                            ->join('evaluation', 'users.id', '=', 'evaluation.evaluate_user_id')
                            ->where(function ($query) use ($role_user) {
                                  $query->where('users.manager_id', '!=',  0)
                                        ->orWhere(function ($query2) use ($role_user) {
                                             $query2->where('users.role', $role_user)
                                                    ->where('users.id', '<>', Auth::user()->id);
                                  });
                              })
                            ->where('evaluation.user_id',   Auth::user()->id)
                            ->where('active', 1)
                            ->orderBy('evaluation.created_at', 'desc')
                            ->get();
        }elseif ($role_user==Config::get('roles.SUPERVISOR')) {
          $users = DB::table('users')
                            ->join('evaluation', 'users.id', '=', 'evaluation.evaluate_user_id')
                            ->where('evaluation.user_id',   Auth::user()->id)
                            ->where('active', 1)
                            ->orderBy('evaluation.created_at', 'desc')

                            ->get();
        }elseif ($role_user==Config::get('roles.DASHBOARD_MANAGER')) {
          $users = DB::table('users')
                            ->join('evaluation', 'users.id', '=', 'evaluation.evaluate_user_id')
                            ->where('evaluation.user_id',   Auth::user()->id)
                            ->where('active', 1)
                            ->orderBy('evaluation.created_at', 'desc')

                            ->get();
        }

        return view('evaluation.page-history',
                        ['users'        => $users,
                        'dateEvaluate'  => $dateEvaluate->format('F Y') ]
                        );
    }


     /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function submitEvaluation(Request $request)
    {
        $data=$request->all(); 
        $dateTime = Carbon::now();

        $evaluation=Evaluation::create([
            'user_id'           => Auth::user()->getId(),
            'evaluate_user_id'  => $data['user'],
            'parameter1'        => $data['param1'],
            'parameter2'        => $data['param2'],
            'parameter3'        => $data['param3'],
            'parameter4'        => $data['param4'],
            'parameter5'        => $data['param5'],
            'description'       => $data['description'],
        ]);   

         return Response::json(
         		array(
         			'status' 		    => '1',
              'evaluation'    =>  $evaluation,
              'dateTime'      =>  date('M j Y g:i A', strtotime($dateTime))
         		)
         	);
    }

     /**
     * Display a listing of the resource.
     *
     * @return Response
     */
     
    public function loadComments(Request $request)
    {
        $data=$request->all(); 

        $evaluations = DB::table('evaluation')
        ->select(DB::raw('*')) 
        ->where('description', '!=',  '')
        ->where('evaluate_user_id',   $data['user_id'])
        ->orderBy('created_at', 'desc')
        ->get();  

        $html=view('evaluation.comments',
                ['evaluations'   => $evaluations]
                )->render();


         return Response::json(
            array('status'        => '1', 'html'  => $html)
        );
    }

}
