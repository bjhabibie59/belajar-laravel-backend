<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
        $bookId = $this->route('book');

        return [
            'isbn' => 'required|string|max:20|unique:books,isbn,' . $bookId,
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:5000',
            'pages' => 'nullable|integer|min:1',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'price' => 'required|numeric|min:0|max:99999999.99',
            'stock' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'isbn.required' => 'ISBN wajib diisi',
            'isbn.unique' => 'ISBN sudah terdaftar',
            'title.required' => 'Judul buku wajib diisi',
            'author_id.required' => 'Penulis wajib dipilih',
            'author_id.exists' => 'Penulis tidak ditemukan',
            'publisher_id.required' => 'Penerbit wajib dipilih',
            'publisher_id.exists' => 'Penerbit tidak ditemukan',
            'category_id.required' => 'Kategori wajib dipilih',
            'category_id.exists' => 'Kategori tidak ditemukan',
            'price.required' => 'Harga wajib diisi',
            'price.min' => 'Harga tidak boleh kurang dari 0',
            'stock.required' => 'Stok wajib diisi',
            'stock.min' => 'Stok tidak boleh kurang dari 0',
            'cover_image.image' => 'File harus berupa gambar',
            'cover_image.mimes' => 'Format gambar harus jpeg, jpg, png, atau webp',
            'cover_image.max' => 'Ukuran gambar maksimal 2MB'
        ];
    }
}
