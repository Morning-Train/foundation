<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use morningtrain\Admin\Extensions\RedirectsAdmins;

class ResetPasswordController extends Controller {
    
    use ResetsPasswords,
        RedirectsAdmins;

    /**
     * Where to redirect users after login.
     *
     * @var  string
     */

    protected $redirectTo = '/';

    /**
     * @return string
     */

    public function redirectPath() {
        return $this->redirectAdmin($this->guard()) ?: $this->redirectTo;
    }

    /**
    * Create a new controller instance.
    *
    * @return  void
    */

    public function __construct() {
        $this->middleware('guest');
    }

}