<?php

namespace App\Http\Middleware;

use App\Models\ViewSong;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddViewSong
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $viewSong = ViewSong::where('song_id', $request->song->id)
            ->where('ip', $request->ip())
            ->get();

        if ($viewSong->count()) {
            return $next($request);
        }

        ViewSong::create([
            'song_id' => $request->song->id,
            'user_id' => Auth::check() ? Auth::user()->id : null,
            'ip' => $request->ip(),
        ]);

        return $next($request);
    }
}
