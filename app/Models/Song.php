<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'preview_image', 'featuring_with', 'producer',
        'text_written_by', 'music_written_by', 'mixed_by',
        'text', 'user_id', 'genre_id'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id', 'id');
    }

    public function ratesUsers()
    {
        return $this->belongsToMany(User::class, 'rate_songs', 'song_id', 'user_id')
            ->withPivot(['user_id', 'song_id', 'rate']);
    }

    public function views()
    {
        return $this->hasMany(ViewSong::class, 'song_id', 'id');
    }

    public function avgRate()
    {
        return round(
            $this->ratesUsers()->avg('rate_songs.rate'),
            2
        );
    }

    public function getRateByUser(?User $user)
    {
        if ($user === null) {
            return 0;
        }

        $rateSong = $this->ratesUsers()->wherePivot('user_id', $user->id)->get()->first();

        return $rateSong
            ? $rateSong->pivot->rate
            : 0;
    }

    public function scopeSearch($query, string $search)
    {
        if (!$search) {
            return $query;
        }

        $search = str_replace(
            [' ', '(', ')'],
            [' & ', '\(', '\)'],
            $search
        ) . ':*';

        return $query->whereRaw("tsvector_text @@ to_tsquery('simple'::regconfig, ?)", [$search])
            ->orderByRaw("ts_rank(tsvector_text, to_tsquery('simple'::regconfig, ?)) DESC", [$search]);
    }

    public function scopeModerated($query)
    {
        return $query->where('is_moderated', true);
    }

    public function scopeNotModerated($query)
    {
        return $query->where('is_moderated', false);
    }
}
