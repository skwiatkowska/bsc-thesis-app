<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Entities\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest')->except('changePassword');
    }

    public function changePassword(Request $request) {
        // dd($request->all());
        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            // The passwords matches
            return back()->withErrors("Podano niepoprawne stare hasło");
        }

        if (strcmp($request->get('current-password'), $request->get('new_password')) == 0) {
            //Current password and new password are same
            return back()->withErrors("Nowe hasło nie może być takie samo jak stare");
        }

        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|same:confirm_password',
            'confirm_password' => 'required'
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = Hash::make($request->get('new_password'));
        $user->save();

        return back()->with("success", "Hasło zostało zmienione!");
    }
}
