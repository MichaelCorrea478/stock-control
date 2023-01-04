<?php

namespace App\Services;

use App\Models\Stock;
use App\Repositories\BrapiApiRepository;
use App\Repositories\StockRepository;
use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\DB;

class StockService
{
    /** @var BrapiApiRepository $brapiApiRepository */
    protected $brapiApiRepository;
    /** @var WalletRepository $walletRepository */
    protected $walletRepository;
    /** @var StockRepository $stockRepository */
    protected $stockRepository;
    /** @var TransactionService $transactionService */
    protected $transactionService;

    public function __construct(TransactionService $transactionService,
                                BrapiApiRepository $brapiApiRepository,
                                StockRepository $stockRepository,
                                WalletRepository $walletRepository)
    {
        $this->brapiApiRepository = $brapiApiRepository;
        $this->walletRepository = $walletRepository;
        $this->transactionService = $transactionService;
        $this->stockRepository = $stockRepository;
    }

    public function getStockList()
    {
        return $this->brapiApiRepository->getStockList();
    }

    public function buyStock($user, $request)
    {
        try {
            DB::transaction(function() use ($user, $request) {
                $this->walletRepository->decrease($user->wallet, $request->value);
                $this->stockRepository->saveBoughtStock($user, $request);
                $this->transactionService->saveBuyStockTransaction($user->id, $request);
            });

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }

    }
}
