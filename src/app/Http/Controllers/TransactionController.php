<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Sell;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index($item_id, Request $request)
    {
        $item_detail = Item::find($item_id);
        $sell_id = Sell::find($item_id)->user_id;
        $user = User::find($sell_id);

        return view('transaction', compact('item_detail', 'user'));
    }
}
