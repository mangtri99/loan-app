<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanRequest extends FormRequest
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
            'type_id' => 'required|exists:types,id',
            'amount' => 'required|numeric',
            'term' => 'required|numeric|min:3|max:24',
        ];
    }

    public function messages(): array
    {
        return [
            'type_id.required' => 'The repayment type field is required.',
            'type_id.exists' => 'The repayment type field is invalid.',
        ];
    }
}
