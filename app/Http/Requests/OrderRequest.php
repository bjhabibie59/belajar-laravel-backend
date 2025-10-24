<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.book_id' => 'required|exists:books,id',
            'items.*.quantity' => 'required|integer|min:1',
            'order_date' => 'nullable|date'
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required' => 'Pelanggan wajib dipilih',
            'customer_id.exists' => 'Pelanggan tidak ditemukan',
            'items.required' => 'Minimal harus ada 1 item pesanan',
            'items.min' => 'Minimal harus ada 1 item pesanan',
            'items.*.book_id.required' => 'Buku wajib dipilih',
            'items.*.book_id.exists' => 'Buku tidak ditemukan',
            'items.*.quantity.required' => 'Jumlah wajib diisi',
            'items.*.quantity.min' => 'Jumlah minimal 1'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('items')) {
                foreach ($this->items as $index => $item) {
                    $book = \App\Models\Book::find($item['book_id'] ?? null);

                    if ($book && $book->stock < ($item['quantity'] ?? 0)) {
                        $validator->errors()->add(
                            "items.{$index}.quantity",
                            "Stok buku '{$book->title}' tidak mencukupi. Stok tersedia: {$book->stock}"
                        );
                    }
                }
            }
        });
    }
}
