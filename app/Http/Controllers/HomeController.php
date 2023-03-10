<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockResource;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getUserInformation()
    {
        $user = auth()->user();
        $wallet = $user->wallet;
        $stocks = $user->stocks;
        $transactions = $user->transactions;

        return response()->json([
            'wallet' => $wallet,
            'stocks' => StockResource::collection($stocks),
            'transactions' => TransactionResource::collection($transactions)
        ]);
    }
}
