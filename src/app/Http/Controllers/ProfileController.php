<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function mypage()
    {
        return view('mypage');
    }

    public function profile()
    {
        return view('mypage/profile');
    }

    public function address($item_buy)
    {
        $item_detail = $item_buy;
        return view('/purchase/address', compact('item_detail'));
    }
}
