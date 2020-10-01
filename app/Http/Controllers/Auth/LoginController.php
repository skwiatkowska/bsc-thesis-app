<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller {
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('userLogout');
        $this->middleware('guest:admin')->except('adminLogout');
    }


    public function showAdminLoginForm() {
        return view('librarian.login');
    }

    public function adminLogin(Request $request) {
        // $this->validate($request, [
        //     'email'   => 'required|email',
        //     'password' => 'required|min:6'
        // ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/pracownik')->with(['success' => 'Zalogowano']);
        }
        return back()->withErrors("Podano błędne dane logowania");
    }


    public function adminLogout () {
        Auth::guard('admin')->logout();
        return redirect('/pracownik/logowanie')->with(['success' => 'Wylogowano']);
    }


    
    public function showUserLoginForm() {
        return view('login');
    }

    public function userLogin(Request $request) {
        // $this->validate($request, [
        //     'email'   => 'required|email',
        //     'password' => 'required|min:6'
        // ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/')->with(['success' => 'Zalogowano']);
        }
        return back()->withErrors("Podano błędne dane logowania");
    }


    public function userLogout () {
        Auth::logout();
        return redirect('/')->with(['success' => 'Wylogowano']);
    }
}
