<?php

namespace App\Repositories;

use App\Models\Api\BrapiApi;

class BrapiApiRepository
{
    public function getStockList()
    {
        try {
            $stocks = (new BrapiApi)->get('/quote/list');
            return $stocks->stocks;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function getCurrentStockPrices(string $stockSymbols)
    {
        $response = (new BrapiApi)->get('/quote/' . $stockSymbols);
        return $response->results;
    }
}
