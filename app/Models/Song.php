<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

     protected $fillable = ['title', 'duration', 'album_id'];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function artist()
    {
        return $this->hasOneThrough(Artist::class, Album::class, 'id', 'id', 'album_id', 'artist_id');
    }
}
