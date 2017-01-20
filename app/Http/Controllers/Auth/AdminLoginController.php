<?php
/**
 * Created by PhpStorm.
 * User: ruudnike
 * Date: 2017-01-19
 * Time: 오후 4:56
 */

namespace App\Http\Controllers\Auth;


use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminLoginController extends LoginController
{
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }
}