<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books|max:20',
            'published_year' => 'required|integer|min:1900|max:' . date('Y'),
        ];
    }

    public function messages() {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'author.required' => 'L\'auteur est obligatoire.',
            'isbn.required' => 'L\'ISBN est obligatoire.',
            'isbn.unique' => 'Ce ISBN est déjà utilisé.',
            'published_year.required' => 'L\'année de publication est obligatoire.',
            'published_year.integer' => 'L\'année doit être un nombre.',
            'published_year.min' => 'L\'année de publication doit être au moins 1900.',
            'published_year.max' => 'L\'année de publication ne peut pas dépasser l\'année actuelle.',
        ];
    }
}
