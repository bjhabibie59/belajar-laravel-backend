<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        $customerId = $this->route('customer');

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customerId,
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'address' => 'nullable|string|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama pelanggan wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'phone.regex' => 'Format nomor telepon tidak valid'
        ];
    }
}
