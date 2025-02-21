<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function mypage(Request $request)
    {
        $data = $request->tab;
        $items = Item::all();
        return view('mypage', compact('items', 'data'));
    }

    public function profile()
    {
        return view('mypage/profile');
    }

    public function tubbuy()
    {
        $items = Item::all();
        return view('mypage', compact('items'));
    }

    public function address($item_buy)
    {
        $item_detail = $item_buy;
        return view('/purchase/address', compact('item_detail'));
    }
}
