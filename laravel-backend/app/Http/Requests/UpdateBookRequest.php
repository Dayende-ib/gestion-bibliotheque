<?php

namespace App\Http\Requests;

use App\Models\Books;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role == 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $bookId = $this->route('book') ? $this->route('book')->id : null;

        return [
            'isbn' => [
                'required',
                Rule::unique('books', 'isbn')->ignore($bookId),
            ],
            'title' => 'required|string|max:254',
            'author' => 'required|string|max:254',
            'published_year' => 'required|integer|min:500|max:' . date('Y'),
            'status' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string|max:515',
        ];
    }

    public function messages() {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne peut pas dépasser 254 caractères.',
            'author.required' => 'L\'auteur est obligatoire.',
            'author.string' => 'L\'auteur doit être une chaîne de caractères.',
            'author.max' => 'L\'auteur ne peut pas dépasser 254 caractères.',
            'isbn.required' => 'L\'ISBN est obligatoire.',
            'isbn.string' => 'L\'ISBN doit être une chaîne de caractères.',
            'isbn.max' => 'L\'ISBN ne peut pas dépasser 13 caractères.',
            'isbn.unique' => 'Ce ISBN existe déjà.',
            'published_year.required' => 'L\'année de publication est obligatoire.',
            'published_year.integer' => 'L\'année de publication doit être un nombre entier.',
            'published_year.min' => 'L\'année de publication doit être au moins 500.',
            'published_year.max' => 'L\'année de publication ne peut pas dépasser l\'année actuelle.',
            'status.required' => 'Le statut est obligatoire.',
            'status.string' => 'Le statut doit être une chaîne de caractères.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format jpeg, png, jpg, webp ou gif.',
            'image.max' => 'L\'image ne peut pas dépasser 2048 kilooctets.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne peut pas dépasser 515 caractères.',
        ];
    }
}