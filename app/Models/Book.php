<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

    // 4. Model Book (app/Models/Book.php)
class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'author_id',
        'publisher_id',
        'category_id',
        'description',
        'pages',
        'publication_year',
        'price',
        'stock',
        'cover_image'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'publication_year' => 'integer',
        'pages' => 'integer',
        'stock' => 'integer'
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Accessor untuk menampilkan harga format Rupiah
    public function getPriceFormattedAttribute()
    {
        return 'Rp ' . number_format((float)$this->price, 0, ',', '.');
    }

    // Scope untuk buku yang tersedia
    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Method untuk cek ketersediaan
    public function isAvailable()
    {
        return $this->stock > 0;
    }

    // Method untuk mengurangi stok
    public function decreaseStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    // Method untuk menambah stok
    public function increaseStock($quantity)
    {
        $this->increment('stock', $quantity);
    }

    // Method untuk mendapatkan rating rata-rata
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}
