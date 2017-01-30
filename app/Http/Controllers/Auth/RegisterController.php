<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use morningtrain\Admin\Extensions\RedirectsAdmins;

class RegisterController extends Controller
{
    
    use RegistersUsers, RedirectsAdmins;
    
    /**
     * Create a new controller instance.
     *
     * @return  void
     */

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Where to redirect users after login.
     *
     * @var  string
     */

    protected $redirectTo = '/';

    /**
     * @return string
     */
    public function redirectPath()
    {
        return $this->redirectAdmin($this->guard()) ?: $this->redirectTo;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param    array $data
     * @return  \Illuminate\Contracts\Validation\Validator
     */

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param    array $data
     * @return  User
     */

    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

}