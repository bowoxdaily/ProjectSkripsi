<?php

namespace App\Http\Controllers\Fe\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function pagelogin()
    {
        return view('auth.login');
    }
    public function pageregister()
    {
        return view('auth.register');
    }
}
