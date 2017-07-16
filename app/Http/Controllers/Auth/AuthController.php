<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Auth;
use Response;
use DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    
    protected $loginPath = '/admin';
    protected $redirectAfterLogout = '/admin';
    protected $redirectPath = '/dashboard';


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users',
            'password'   => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name'   => $data['first_name'],
            'middle_name'  => $data['middle_name'],
            'last_name'    => $data['last_name'],
            'email'        => $data['email'],
            'password'     => bcrypt($data['password']),
        ]);
    }

     /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $data=$request->all();
        if($data['employee_number']!="" && $data['employee_number']!=0){
            $emailUser="";
            $role=0;
            $queryUser= DB::table('users')
            ->where('employee_number', $data['employee_number'])
            ->where('active', 1)
            ->get();
            foreach ($queryUser as $value) {
                $emailUser=$value->email;
                $role=$value->role;         
            }
            //if($role!=9)
                $request->merge([ 'email' => $emailUser]);
            
            $this->validate($request, [
                'employee_number' => 'required', 'password' => 'required',
            ]);
        }else{
            $this->validate($request, [
                $this->loginUsername() => 'required', 'password' => 'required',
            ]);    
        }
        

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return Response::json(array('status' => '0', 'message' =>   $this->sendLockoutResponse($request)));
        }


         
        $credentials = $this->getCredentials($request);
        if (Auth::attempt($credentials, $request->has('remember'))) {
            return Response::json(array('status' => '1', 'message'  =>   ''));
        }else{
             if ($throttles) {
                $this->incrementLoginAttempts($request);
            }
            return Response::json(array('status' => '0', 'message' =>   $this->getFailedLoginMessage()));
        }

    }
}
