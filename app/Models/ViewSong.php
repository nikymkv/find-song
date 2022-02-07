<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewSong extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_id',
        'user_id',
        'ip',
    ];
}
