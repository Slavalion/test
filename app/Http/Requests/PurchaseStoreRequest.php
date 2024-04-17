<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'products.*.remote_id' => ['required'],
            'products.*.quantity' => ['required'],
            'products.*.gender.id' => ['required'],
            // 'products.*.size.id' => ['required'],
            'products.*.keywords' => ['required'],
            'products.*.address' => ['required'],
            'products.*.purchase_at' => ['required'],
        ];
    }
}
