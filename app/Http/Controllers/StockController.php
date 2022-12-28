<?php

namespace App\Http\Controllers;

use App\Services\StockService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        return view('stocks.index');
    }

    public function list(StockService $stockService)
    {
        return $stockService->getStockList();
    }
}
