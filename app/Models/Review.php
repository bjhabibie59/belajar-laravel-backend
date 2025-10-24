<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\ReviewRequest;

class Review extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'book_id',
        'customer_id',
        'rating',
        'comment'
    ];

    protected $casts = [
        'rating' => 'integer'
    ];

    // Relationships
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
