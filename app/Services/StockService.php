<?php

namespace App\Services;

use App\Repositories\BrapiApiRepository;
use App\Repositories\WalletRepository;

class StockService
{
    /** @var BrapiApiRepository $brapiApiRepository */
    protected $brapiApiRepository;
    /** @var WalletRepository $walletRepository */
    protected $walletRepository;
    /** @var TransactionService $transactionService */
    protected $transactionService;

    public function __construct(TransactionService $transactionService,
                                BrapiApiRepository $brapiApiRepository,
                                WalletRepository $walletRepository)
    {
        $this->brapiApiRepository = $brapiApiRepository;
        $this->walletRepository = $walletRepository;
        $this->transactionService = $transactionService;
    }

    public function getStockList()
    {
        return $this->brapiApiRepository->getStockList();
    }

    public function buyStock($user, $stock)
    {
        try {
            $this->walletRepository->decrease($user->wallet, $stock->value);

            $user->stocks()->create([
                'symbol' => $stock->stock_symbol,
                'quantity' => $stock->quantity
            ]);

            $this->transactionService->saveBuyStockTransaction($user->id, $stock);

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }

    }
}
