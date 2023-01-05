<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function __construct(Transaction $model)
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
            'user_id' => $this->user_id,
            'transaction_type_id' => $this->transaction_type_id,
            'stock_symbol' => $this->stock_symbol,
            'transaction_type' => $this->type->description,
            'quantity' => $this->quantity,
            'value' => $this->value,
            'date' => Carbon::parse($this->created_at)->locale('pt_BR')->format('j \d\e F, Y g:i\h'),
        ];
    }
}
