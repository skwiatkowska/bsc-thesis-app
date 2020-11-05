<?php

namespace App\Http\Controllers\Auth;

use App\Entities\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function showUserRegisterForm() {
        return view('/user/register');
    }

    protected function createUser(Request $request) {
        // $this->validate($request, [
        //     'name' => 'required|min:3|max:50',
        //     'email' => 'email',
        //     'password' => 'confirmed|min:6',
        // ]);

        User::create([
            'first_name' => $request->fname,
            'last_name' => $request->lname,
            'pesel' => $request->pesel,
            'phone' => $request->phone,
            'email' => $request->email,
            'street' => $request->street,
            'house_number' => $request->house_number,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'password' => Hash::make($request->password),
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            return redirect()->intended('/dane')->with(['success' => 'Witamy. Konto zostało poprawnie utworzone']);
        }
    }
}
