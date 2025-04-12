<?php

namespace App\Http\Resources;

use App\Enums\StatusOrderEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order' => [
                'id' => data_get($this, 'id'),
                'user_id' => data_get($this, 'user_id'),
                'costumer_name' => data_get($this, 'costumer_name'),
                'destination_name' => data_get($this, 'destination_name'),
                'departure_date' => data_get($this, 'departure_date'),
                'status' => StatusOrderEnum::label(data_get($this, 'status')),
            ]
        ];
    }
}
