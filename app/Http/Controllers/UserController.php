<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserProfile;
use Validator;
use Auth;
use Config;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        $result = User::where('role','<', '9')->where('active', '1')->orderBy('role', 'asc')->paginate(25);
        $roles = array(
            '0' =>  "N/A",
            '1' =>  "Administrator",
            '2' =>  "Financial",
            '3' =>  "User",
            '4' =>  "Dir Pos",
            '5' =>  "Area Supervisor",
            '6' =>  "Area Manager",
            '7' =>  "Dashboard Manager",
            '8' =>  "Supervisor",
            '9' =>  "Employee",
        );
        return view('users.list', ['users'  => $result, 'roles'  => $roles]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function listEmployee()
    {
        $result = User::where('role', '9')->where('active', '1')->orderBy('first_name', 'asc')->paginate(25);
        $roles = array(
            '0' =>  "N/A",
            '1' =>  "Administrator",
            '2' =>  "Financial",
            '3' =>  "User",
            '4' =>  "Dir Pos",
            '5' =>  "Area Supervisor",
            '6' =>  "Area Manager",
            '7' =>  "Dashboard Manager",
            '8' =>  "Supervisor",
            '9' =>  "Employee",
        );
        return view('users.list', ['users'  => $result, 'roles'  => $roles]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function dashboard()
    {
        $param = array(
            'param' =>  "1",
        );    
        return view('dashboard.home', ['param'  => $param]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
         $validator = $this->validator_register($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $data=$request->all();
        $user=User::create([
            'first_name'      => $data['first_name'],
            'middle_name'     => $data['middle_name'],
            'last_name'       => $data['last_name'],
            'email'           => $data['email'],
            'employee_number' => $data['employee_number'],
            'primary_job'     => $data['primary_job'],
            'role'            => $data['role'],
            'manager_id'      => $data['manager_id'],
            'password'        => bcrypt($data['password']),
        ]);

        //Insert Profile
        UserProfile::create([
            'user_id'       => $user->id,
            'note'          => $data['note'],
        ]);

        return redirect('/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
         return view('users.edit', ['user' => User::with('profile')->findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validator_update($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $user=User::findOrFail($id);
        $data=$request->all();

        $user->first_name=$data['first_name'];
        $user->middle_name=$data['middle_name'];
        $user->last_name=$data['last_name'];
        $user->employee_number=$data['employee_number'];
        $user->primary_job=$data['primary_job'];
        $user->role=$data['role'];
        $user->manager_id=$data['manager_id'];
        if($data['password']==$data['password_confirmation'] && $data['password']!=""){
            $user->password=bcrypt($data['password']);
        }
        
        $user->save();
        return redirect('/users')->with('status', 'User has been updated!');;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id)
    {
         return view('users.delete', ['user' => User::findOrFail($id)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $user=User::findOrFail($id);
        $user->delete();
        return redirect('/users');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function profile()
    {
         return view('users.profile', ['user' => User::with('profile')->findOrFail(Auth::user()->id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */

    public function saveProfile(Request $request)
    {
        $msg="";
        $user=User::findOrFail(Auth::user()->id);
        $data=$request->all();

        $user->first_name=$data['first_name'];
        $user->last_name=$data['last_name'];
        if($data['password']!=""){
            if($data['password']==$data['password_confirmation']){
                $user->password= bcrypt($data['password']);
            }else{
                $msg="Passwords do not match, please try again.";
            }
        }

        if($msg==""){
            $msg="User has been updated!";
        }

        $user->save();
        return redirect('/user/profile')->with('status', $msg);;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_register(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users',
            'role'       => 'required',
            'password'   => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Get a validator for an incoming update request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_update(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'role'       => 'required',
        ]);
    }
}
