<?php

namespace App\Http\Controllers;

use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function getUserWallet()
    {
        return auth()->user()->wallet->toJson();
    }

    public function makeDeposit(Request $request, WalletService $walletService)
    {
        $wallet = auth()->user()->wallet;
        $result = $walletService->makeDeposit(
            $wallet,
            $request->value
        );
        return response()->json([
            'success' => $result,
            'wallet' => $wallet->refresh()
        ]);
    }

    public function makeWithdraw(Request $request, WalletService $walletService)
    {
        $wallet = auth()->user()->wallet;
        $result = $walletService->makeWithdraw(
            $wallet,
            $request->value
        );
        return response()->json([
            'success' => $result,
            'wallet' => $wallet->refresh()
        ]);
    }
}
