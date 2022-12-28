<?php

namespace App\Services;

use App\Repositories\BrapiApiRepository;

class StockService
{
    /** @var BrapiApiRepository $brapiApiRepository */
    protected $brapiApiRepository;

    public function __construct(BrapiApiRepository $brapiApiRepository)
    {
        $this->brapiApiRepository = $brapiApiRepository;
    }
    public function getStockList()
    {
        return $this->brapiApiRepository->getStockList();
    }
}
