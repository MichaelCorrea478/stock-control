<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeWithdrawRequest;
use App\Http\Resources\TransactionResource;
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
        $user = auth()->user();
        $wallet = $user->wallet;
        $result = $walletService->makeDeposit(
            $wallet,
            $request->value
        );
        return response()->json([
            'success' => $result,
            'wallet' => $wallet->refresh(),
            'transactions' => TransactionResource::collection($user->transactions)
        ]);
    }

    public function makeWithdraw(MakeWithdrawRequest $request, WalletService $walletService)
    {
        $user = auth()->user();
        $wallet = $user->wallet;
        $result = $walletService->makeWithdraw(
            $wallet,
            $request->safe()->only(['value'])['value']
        );
        return response()->json([
            'success' => $result,
            'wallet' => $wallet->refresh(),
            'transactions' => TransactionResource::collection($user->transactions)
        ]);
    }
}
