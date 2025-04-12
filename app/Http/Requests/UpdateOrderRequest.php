<?php

namespace App\Http\Requests;

use App\Enums\StatusOrderEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'costumer_name' => 'required|string|max:255',
            'destination_name' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after:departure_date',
            'status' => 'required|in:'.StatusOrderEnum::REQUESTED->value.','.StatusOrderEnum::APPROVED->value.','.StatusOrderEnum::CANCELED->value,
        ];
    }
}
