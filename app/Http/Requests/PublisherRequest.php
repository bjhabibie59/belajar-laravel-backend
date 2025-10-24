<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublisherRequest extends FormRequest
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
        $publisherId = $this->route('publisher');

        return [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'email' => 'nullable|email|unique:publishers,email,' . $publisherId
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama penerbit wajib diisi',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar'
        ];
    }
}
