<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'preview_image',
        'about',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPassword(string $value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function songs()
    {
        return $this->hasMany(Song::class, 'user_id', 'id');
    }

    public function ratedSongs()
    {
        return $this->belongsToMany(Song::class, 'rate_songs', 'user_id', 'song_id')
            ->withPivot(['rate']);
    }

    public function moderatedSongs()
    {
        return $this->songs()->moderated()->get();
    }

    public function ratedUsers()
    {
        return $this->belongsToMany(User::class, 'rate_users', 'user_id', 'rated_by_user_id')
            ->withPivot(['rated_by_user_id', 'user_id', 'rate']);
    }

    public function avgRate()
    {
        return round(
            $this->ratedUsers()->avg('rate_users.rate'),
            2
        );
    }

    public function getRateByUser(?User $user)
    {
        if ($user === null) {
            return 0;
        }

        $rateUser = $this->ratedUsers()->wherePivot('rated_by_user_id', '=', $user->id)->get()->first();

        return $rateUser
            ? $rateUser->pivot->rate
            : 0;
    }

    public function favouritesSongs()
    {
        return $this->belongsToMany(Song::class, 'favourite_songs', 'user_id', 'song_id');
    }

    public function inFavouritesSongs(Song $song): bool
    {
        $favourite = $this->favouritesSongs()
            ->wherePivot('song_id', '=', $song->id)
            ->get()
            ->first();

        return $favourite
            ? true
            : false;
    }
}
