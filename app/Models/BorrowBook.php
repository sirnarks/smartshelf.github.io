<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowBook extends Model
{
    use HasFactory;

    protected $table = 'borrow_book'; // keep this if your table name is not plural

    protected $fillable = [
        'borrow_id',
        'book_id',
    ];

    /**
     * Get the borrow record that owns this borrowed book.
     */
public function borrow()
{
    return $this->belongsTo(\App\Models\Borrow::class);
}

public function book()
{
    return $this->belongsTo(\App\Models\Book::class);
}


}