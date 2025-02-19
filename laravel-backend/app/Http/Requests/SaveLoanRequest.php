<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveLoanRequest extends FormRequest
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
            'book_id' => 'required|unique:loans|integer',
            'loan_date' => 'required|date',
            'return_date' => 'required|date',
        ];
    }

    public function messages() {
        return [
            'book_id.required' => 'Le titre est obligatoire.',
            'loan_date.required' => 'L\'auteur est obligatoire.',
            'return_date.required' => 'L\'ISBN est obligatoire.',
            'book_id.unique' => 'Ce livre est déjà emprunté.',
        ];
    }
}
