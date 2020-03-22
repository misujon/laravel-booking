<?php

namespace App\Http\Controllers\backend\Auth;

use App\Http\Controllers\Controller;
use App\Mi_admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

//    public function __construct()
//    {
//        $this->middleware('guest:admin')->except('admin_logout');
//    }


    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }else{
            return view('admin.login');
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'admin_email' => 'required|string|email',
            'admin_pass' => 'required|string'
        ]);


        if (Mi_admin::where('admin_email', $request->admin_email)->exists()) {
            $status = Mi_admin::where('admin_email', '=', $request->admin_email)->first();

            if ($status->status == 1) {

                if (Auth::guard('admin')->attempt(['admin_email' => $request->admin_email, 'password' => $request->admin_pass])) {
                    session()->flash('success', 'You are logged in successfully');
                    return redirect()->route('dashboard');
                } else {
                    session()->flash('error', 'Invelid Credentials');
                    return redirect()->route('admin_login');
                }
            }else{
                session()->flash('error', 'Sorry! you are not activated yet!');
                return redirect()->route('admin_login');
            }
        } else {
            session()->flash('error', 'User not exists');
            return redirect()->route('admin_login');
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        session()->flash('success', 'Logout Successfully');
        return redirect('/admin');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }
}
