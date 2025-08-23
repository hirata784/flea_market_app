<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;


class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->tab;
        $items = Item::all();
        $user = User::find(Auth::id());
        return view('profile', compact('items', 'data', 'user'));
    }


    public function re_verified(Request $request)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        if (!$user['email_verified_at']) {
            // メール認証していなければ認証画面へ
            return view('verify-email');
        } else {
            // メール認証していれば商品一覧画面へ
            $items = Item::all();
            $user_id = Auth::id();
            $data = $request->tab;
            return view('list', compact('items', 'user_id', 'data'));
        }
    }


}
