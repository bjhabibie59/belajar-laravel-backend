<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $authorId = $this->route('author');

        return [
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string|max:2000',
            'email' => 'nullable|email|unique:authors,email,' . $authorId
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama penulis wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar'
        ];
    }
}
