<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
        return [
            'book_id' => 'required|exists:books,id',
            'customer_id' => 'required|exists:customers,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'book_id.required' => 'Buku wajib dipilih',
            'book_id.exists' => 'Buku tidak ditemukan',
            'customer_id.required' => 'Pelanggan wajib dipilih',
            'customer_id.exists' => 'Pelanggan tidak ditemukan',
            'rating.required' => 'Rating wajib diisi',
            'rating.min' => 'Rating minimal 1',
            'rating.max' => 'Rating maksimal 5',
            'comment.max' => 'Komentar maksimal 1000 karakter'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Cek apakah customer sudah pernah review buku ini
            $existingReview = \App\Models\Review::where('book_id', $this->book_id)
                ->where('customer_id', $this->customer_id)
                ->where('id', '!=', $this->route('review'))
                ->exists();

            if ($existingReview) {
                $validator->errors()->add(
                    'book_id',
                    'Anda sudah memberikan review untuk buku ini'
                );
            }
        });
    }
}
