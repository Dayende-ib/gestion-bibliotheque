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
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'borrowed_at' => 'required|date',
            'due_date' => 'required|date|after_or_equal:loan_date',
        ];
    }

    public function messages() {
        return [
            'book_id.required' => 'Le livre est obligatoire.',
            'book_id.exists' => 'Le livre sélectionné n\'existe pas.',
            'member_id.required' => 'Le membre est obligatoire.',
            'member_id.exists' => 'Le membre sélectionné n\'existe pas.',
            'borrowed_at.required' => 'La date d\'emprunt est obligatoire.',
            'borrowed_at.date' => 'La date d\'emprunt doit être une date valide.',
            'due_date.required' => 'La date de retour est obligatoire.',
            'due_date.date' => 'La date de retour doit être une date valide.',
            'due_date.after_or_equal' => 'La date de retour doit être égale ou postérieure à la date d\'emprunt.',
        ];
    }
}
