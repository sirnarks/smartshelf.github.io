<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'copies',
        'isbn',
        'rack_id',
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }

    protected static function booted()
    {
        static::creating(function ($book) {
            $firstLetter = strtoupper(substr($book->title, 0, 1));

            if (preg_match('/[A-E]/', $firstLetter)) {
                $rackName = 'A–E';
            } elseif (preg_match('/[F-J]/', $firstLetter)) {
                $rackName = 'F–J';
            } elseif (preg_match('/[K-O]/', $firstLetter)) {
                $rackName = 'K–O';
            } elseif (preg_match('/[P-T]/', $firstLetter)) {
                $rackName = 'P–T';
            } elseif (preg_match('/[U-Z]/', $firstLetter)) {
                $rackName = 'U–Z';
            } else {
                $rackName = 'General';
            }

            $rack = \App\Models\Rack::firstOrCreate(['name' => $rackName]);
            $book->rack_id = $rack->id;
        });
    }
}
