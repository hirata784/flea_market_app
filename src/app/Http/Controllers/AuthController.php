<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function profile()
    {
        return view('mypage/profile');
    }
}
