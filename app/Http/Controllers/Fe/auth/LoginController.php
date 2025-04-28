<?php

namespace App\Http\Controllers\Fe\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SebastianBergmann\CodeUnit\FunctionUnit;

class LoginController extends Controller
{
    public function pagelogin()
    {
        return view('auth.login');
    }
}
