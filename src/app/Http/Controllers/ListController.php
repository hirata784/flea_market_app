<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->session()->get('keyword');
        if (!$search) {
            $items = Item::all();
        } else {
            // 検索状態保持
            $items = Item::KeywordSearch($search)->get();
        }
        $user_id = Auth::id();
        $data = $request->tab;
        return view('list', compact('items', 'user_id', 'data'));
    }

    public function indexSearch(Request $request)
    {
        $items = Item::KeywordSearch($request->keyword)->get();
        $data = $request->tab;
        // 検索状態保持
        $request->session()->put('keyword', $request->keyword);
        return view('list', compact('items', 'data'));
    }
}
