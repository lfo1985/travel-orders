<?php

namespace App\Http\Resources;

use App\Enums\StatusOrderEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'orders' => $this->collection->map(function($order){
                return [
                    'id' => data_get($order, 'id'),
                    'user_id' => data_get($order, 'user_id'),
                    'costumer_name' => data_get($order, 'costumer_name'),
                    'destination_name' => data_get($order, 'destination_name'),
                    'departure_date' => data_get($order, 'departure_date'),
                    'return_date' => data_get($order, 'return_date'),
                    'status' => StatusOrderEnum::label(data_get($order, 'status')),
                ];
            }),
            'total' => $this->total(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
        ];
    }
}
