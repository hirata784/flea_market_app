<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;

class EditController extends Controller
{
    public function index()
    {
        $user_all = User::all();
        $user_id = Auth::id();
        $user = $user_all[$user_id - 1];
        return view('mypage/edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $items = Item::all();
        $data = $request->tab;
        $form = $request->all();
        unset($form['_token']);
        User::find($request->id)->update($form);

        // 画像ファイルの保存場所指定
        if (request('profile_img')) {
            $filename = request()->file('profile_img')->getClientOriginalName();
            $inputs['profile_img'] = request('profile_img')->storeAs('public/images', $filename);
            User::find($request->id)->update($inputs);
        }
        return redirect()->action([ListController::class, 'index']);
    }
}
