<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutOrderRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['nullable', 'string', 'max:100'],
            'order_type' => ['required', 'in:dine_in,take_away'],
            'payment_method' => ['required', 'in:cash,qris,debit,credit,ewallet'],
            'payment_amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'items.*.temperature' => ['nullable', 'in:Hot,Ice'],
            'items.*.modifiers' => ['nullable', 'array'],
            'items.*.modifiers.*' => ['exists:modifiers,id'],
            'items.*.notes' => ['nullable', 'string', 'max:255'],
        ];
    }
}
