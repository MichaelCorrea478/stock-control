<?php

namespace App\Http\Controllers;

use App\Services\StockService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /** @var StockService $stockService */
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }
    public function index()
    {
        return view('stocks.index');
    }

    public function list()
    {
        return $this->stockService->getStockList();
    }

    public function buyStock(Request $request)
    {
        return $this->stockService->buyStock(
            auth()->user(),
            $request
        );
    }

    public function getCurrentPrices()
    {
        return $this->stockService->getCurrentStockPrices(auth()->user());
    }

    public function sellStock(Request $request)
    {
        # code...
    }
}
