<?php

namespace App\Http\Resources;

use App\Models\Stock;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    public function __construct(Stock $model)
    {
        parent::__construct($model);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'logo' => $this->logo,
            'symbol' => $this->symbol,
            'sector' => $this->sector,
            'quantity' => $this->quantity,
            'current_price' => 0,
            'total' => 0
        ];
    }
}
