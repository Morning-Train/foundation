<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use morningtrain\Admin\Extensions\RedirectsAdmins;

class LoginController extends Controller
{
    
    use AuthenticatesUsers, RedirectsAdmins;

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
     * Create a new controller instance.
     *
     * @return  void
     */

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

}