<?php

namespace App\Http\Requests;

use App\Enums\StatusOrderEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusOrderRequest extends FormRequest
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
            'status' => 'required|in:'.StatusOrderEnum::REQUESTED->value.','.StatusOrderEnum::APPROVED->value.','.StatusOrderEnum::CANCELED->value,
        ];
    }
}
